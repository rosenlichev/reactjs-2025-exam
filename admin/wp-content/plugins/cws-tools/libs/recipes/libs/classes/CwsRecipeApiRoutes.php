<?php

class CwsRecipeApiRoutes extends CwsAbstractBaseApiService
{
	public function add_api_routes()
	{
		register_rest_route($this->namespace, 'getRecipes', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsGetRecipes'],
            'args'                => [],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route($this->namespace, 'getMyRecipes', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsGetMyRecipes'],
            'args'                => [],
            'permission_callback' => [$this, 'check_bearer_token']
        ]);

        register_rest_route($this->namespace, 'getRecipesHomepage', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsGetRecipesHomepage'],
            'args'                => [],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route($this->namespace, 'getRecipeDetails', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsGetRecipeDetails'],
            'args'                => [],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route($this->namespace, 'saveOrUpdateRecipe', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsSaveOrUpdateRecipe'],
            'args'                => [],
            'permission_callback' => [$this, 'check_bearer_token']
        ]);

        register_rest_route($this->namespace, 'addComment', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsAddComment'],
            'args'                => [],
            'permission_callback' => [$this, 'check_bearer_token']
        ]);

        register_rest_route($this->namespace, 'setLiked', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsSetLiked'],
            'args'                => [],
            'permission_callback' => [$this, 'check_bearer_token']
        ]);
	}

	public function cwsToolsGetRecipes(WP_REST_Request $request)
	{
		$_params 	= $request->get_json_params();
		$params 	= [];

		$recipes = (new CwsRecipeService)->getRecipes($params);

		$response = $recipes;

		$this->api_send_json('getRecipes', $response ?? [], $status ?? 200);
	}


	public function cwsToolsGetMyRecipes(WP_REST_Request $request)
	{
		$_params 	= $request->get_json_params();
		$params 	= [];

		if (is_user_logged_in()) {
			$params['author'] = get_current_user_id();

			$recipes = (new CwsRecipeService)->getRecipes($params);

			$response = $recipes;
		} else {
			$response = [];
		}

		$this->api_send_json('getMyRecipes', $response ?? [], $status ?? 200);
	}

	public function cwsToolsGetRecipesHomepage(WP_REST_Request $request)
	{
		$_params 	= $request->get_json_params();
		$params 	= [];

		$params['posts_per_page'] = 6;
		$params['orderby'] = 'rand';

		$recipes = (new CwsRecipeService)->getRecipes($params);

		$response = $recipes;

		$this->api_send_json('getRecipesHomepage', $response ?? [], $status ?? 200);
	}

	public function cwsToolsGetRecipeDetails(WP_REST_Request $request)
	{
		$_params = $request->get_json_params();
		$params = [];

		if (isset($_params['id']) && $_params['id'] != '') {
            $params['id'] = $_params['id'] ?? 0;
        }

        $recipe = (new CwsRecipeService)->getRecipeDetails($params);

        $response = $recipe;

		$this->api_send_json('getRecipeDetails', $response ?? [], $status ?? 200);
	}

	public function cwsToolsSaveOrUpdateRecipe(WP_REST_Request $request)
	{
		$params = $request->get_json_params();

		$CwsRecipeService = new CwsRecipeService();
		$CwsRecipeService->setVars($params);

		$responseData = $CwsRecipeService->saveOrUpdateRecipe();

		if ($responseData->has('errors') && $responseData->get('errors')->isNotEmpty()) {

            $response   = ['message' => $responseData->get('errors')->first()];
            $status     = 400;

            $this->api_send_json('registerUser', $response, $status ?? 200);
            die();

        } else {

            $response = $responseData->get('data');
        }

		$this->api_send_json('saveOrUpdateRecipe', $response ?? [], $status ?? 200);
	}

	public function cwsToolsAddComment(WP_REST_Request $request)
	{
		$params = $request->get_json_params();

		if (!isset($params['comment_content']) || $params['comment_content']  ==  '') {
			$response   = ['message' => __('Please write your comment before submitting it', 'cws-tools')];
            $status     = 400;

            $this->api_send_json('addComment', $response, $status ?? 200);
            die();
		} elseif (!isseT($params['id']) || $params['id'] == 0) {
			$response   = ['message' => __('You are submitting a comment to an invalid Recipe', 'cws-tools')];
            $status     = 400;

            $this->api_send_json('addComment', $response, $status ?? 200);
            die();
		} else {
			$user_id = get_current_user_id();

			$comment_fields = [
				'comment_post_ID' 	=> $params['id'],
				'comment_author' 	=> $user_id,
				'comment_content' 	=> $params['comment_content'],
				'comment_type' 		=> '',
				'user_id' 			=> $user_id,
				'comment_approved' 	=> 1
			];

			$comment_id = wp_insert_comment($comment_fields);

			if ($comment_id) {
				$recipe = (new CwsRecipeService)->getRecipeDetails($params);

		        $response = $recipe;

		        $response->put('message', __('Comment has been submitted', 'cws-tools'));

				$this->api_send_json('addComment', $response ?? [], $status ?? 200);
			} else {
				$response   = ['message' => __('Your comment could not be submitted at this time. Please try again later.', 'cws-tools')];
	            $status     = 400;

	            $this->api_send_json('addComment', $response, $status ?? 200);
	            die();
			}
		}

		$this->api_send_json('addComment', $response ?? [], $status ?? 200);
	}

	public function cwsToolsSetLiked(WP_REST_Request $request)
	{
		$params = $request->get_json_params();

		$CwsRecipeService = new CwsRecipeService();
		$CwsUserService = new CwsUserService();

		$user_id 	= get_current_user_id();
		$post_id 	= $params['id'] ?? 0;
		$mode 		= $params['mode'] ?? 'add';

		$manageWishlist = $CwsUserService->manageUserWishlist($user_id, $post_id, $mode);

		if ($manageWishlist) {
			$recipe = $CwsRecipeService->getRecipeDetails($params);

        	$response = $recipe;

			$this->api_send_json('getRecipeDetails', $response ?? [], $status ?? 200);
		} else {
			$response   = ['message' => __('Invalid action', 'cws-tools')];
            $status     = 400;

            $this->api_send_json('setLiked', $response, $status ?? 200);
            die();
		}

		$this->api_send_json('addComment', $response ?? [], $status ?? 200);
	}
}
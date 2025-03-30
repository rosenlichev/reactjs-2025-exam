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
        	if (!empty($_FILES) && $responseData->get('data')->has('id')) {
        		$post_id = $responseData->get('data')->get('id');
        		$CwsRecipeService->saveRecipeImage($post_id, $_FILES);
        	}

            $response = $responseData->get('data');
        }

		$this->api_send_json('saveOrUpdateRecipe', $response ?? [], $status ?? 200);
	}

	public function cwsToolsAddComment(WP_REST_Request $request)
	{
		$_params = $request->get_json_params();
		$params = [];

		$this->api_send_json('addComment', $response ?? [], $status ?? 200);
	}
}
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
            'permission_callback' => '__return_true'
        ]);

        register_rest_route($this->namespace, 'addComment', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsAddComment'],
            'args'                => [],
            'permission_callback' => '__return_true'
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
		$_params = $request->get_json_params();
		$params = [];

		$this->api_send_json('saveOrUpdateRecipe', $response ?? [], $status ?? 200);
	}

	public function cwsToolsAddComment(WP_REST_Request $request)
	{
		$_params = $request->get_json_params();
		$params = [];

		$this->api_send_json('addComment', $response ?? [], $status ?? 200);
	}
}
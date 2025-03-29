<?php

class CwsUserApiRoutes extends CwsAbstractBaseApiService
{
	public function add_api_routes()
	{
		register_rest_route($this->namespace, 'registerUser', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsRegisterUser'],
            'args'                => [],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route($this->namespace, 'loginUser', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsLoginUser'],
            'args'                => [],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route($this->namespace, 'getUser', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsGetUser'],
            'args'                => [],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route($this->namespace, 'updateUser', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsUpdateUser'],
            'args'                => [],
            'permission_callback' => '__return_true'
        ]);
	}

    public function cwsToolsRegisterUser(WP_REST_Request $request)
    {
        $_params = $request->get_json_params();
        $params = [];

        $this->api_send_json('registerUser', $response ?? [], $status ?? 200);
    }

    public function cwsToolsLoginUser(WP_REST_Request $request)
    {
        $_params = $request->get_json_params();
        $params = [];

        $this->api_send_json('loginUser', $response ?? [], $status ?? 200);
    }

    public function cwsToolsGetUser(WP_REST_Request $request)
    {
        $_params = $request->get_json_params();
        $params = [];

        $this->api_send_json('getUser', $response ?? [], $status ?? 200);
    }

    public function cwsToolsUpdateUser(WP_REST_Request $request)
    {
        $_params = $request->get_json_params();
        $params = [];

        $this->api_send_json('updateUser', $response ?? [], $status ?? 200);
    }
}
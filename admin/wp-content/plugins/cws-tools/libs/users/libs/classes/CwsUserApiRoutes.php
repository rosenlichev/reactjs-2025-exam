<?php

class CwsUserApiRoutes extends CwsAbstractBaseApiService
{
	public function add_api_routes()
	{
		register_rest_route($this->namespace, 'registerUser', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsRegisterUser'],
            'args'                => [],
            'permission_callback' => '__return_true',
            'args'                => [
                'first_name'            => [
                    'required'          => true,
                    'sanitize_callback' => 'sanitize_text_field'
                ],
                'last_name'             => [
                    'required'          => true,
                    'sanitize_callback' => 'sanitize_text_field'
                ],
                'email'                 => [
                    'required'          => true,
                    'sanitize_callback' => 'sanitize_email'
                ],
                'password'              => [
                    'required'          => true,
                    'sanitize_callback' => 'sanitize_text_field'
                ],
                'confirm_password'      => [
                    'required'          => true,
                    'sanitize_callback' => 'sanitize_text_field'
                ],
            ]
        ]);

        register_rest_route($this->namespace, 'loginUser', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsLoginUser'],
            'args'                => [],
            'permission_callback' => '__return_true',
            'args'                => [
                'email'                 => [
                    'required'          => true,
                    'sanitize_callback' => 'sanitize_email'
                ],
                'password'              => [
                    'required'          => true,
                    'sanitize_callback' => 'sanitize_text_field'
                ],
            ]
        ]);

        register_rest_route($this->namespace, 'logoutUser', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsLogoutUser'],
            'permission_callback' => [$this, 'check_bearer_token']
        ]);

        register_rest_route($this->namespace, 'getUser', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsGetUser'],
            'args'                => [],
            'permission_callback' => [$this, 'check_bearer_token']
        ]);

        register_rest_route($this->namespace, 'updateUser', [
            'methods'             => 'POST',
            'callback'            => [$this, 'cwsToolsUpdateUser'],
            'args'                => [],
            'permission_callback' => [$this, 'check_bearer_token']
        ]);
	}

    public function cwsToolsRegisterUser(WP_REST_Request $request)
    {
        $cwsUserService = new CwsUserService();
        $cwsUserService->setVars($request->get_json_params());

        $responseData = $cwsUserService->createUser();

        if ($responseData->has('errors') && $responseData->get('errors')->isNotEmpty()) {

            $response   = ['message' => $responseData->get('errors')->first()];
            $status     = 400;

            $this->api_send_json('registerUser', $response, $status ?? 200);
            die();

        } else {
            $response = $responseData->get('data');
        }

        $this->api_send_json('registerUser', $response ?? [], $status ?? 200);
    }

    public function cwsToolsLoginUser(WP_REST_Request $request)
    {
        $cwsUserService = new CwsUserService();

        $responseData = $cwsUserService->loginUser(collect($request->get_json_params()));

        if ($responseData->has('errors') && $responseData->get('errors')->isNotEmpty()) {

            $response   = ['message' => $responseData->get('errors')->first()];
            $status     = 400;

            $this->api_send_json('loginUser', $response, $status ?? 200);
            die();

        } else {
            $response = $responseData->get('data');
        }

        __sd($response, 'cwsLoginUser');

        $this->api_send_json('loginUser', $response ?? [], $status ?? 200);
    }

    public function cwsToolsLogoutUser(WP_REST_Request $request)
    {
        $response = [];

        $this->api_send_json('logoutUser', $response, $status ?? 200);
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
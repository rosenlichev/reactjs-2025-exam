<?php

use Illuminate\Support\Collection;

class CwsUserService extends CwsBaseService
{
	public $user_id         = null;
    public $user_data       = null;
    private $jwtAuth        = 'jwt-aut';
    private $jwtAuthVersion = '1.3.6';

    public function __construct($user_id = null)
    {
        if (is_int($user_id) && $user_id > 0) {
            $this->user_id   = $user_id;
            $this->user_data = $this->getUser($this->user_id);
        }

        parent::__construct();
    }

	public function createUser()
	{
		$response = new Collection(['errors' => collect(), 'data' => collect()]);

        $fields = $this->setFields();
        $errors = $this->validateUser($fields);

        if ($errors->isEmpty()) {
            $user = $this->createUserInWordpress($fields);
            $response->put('data', $user);

        } else {

            $response->put('errors', $errors);
        }

        return $response;
	}

    public function loginUser($request)
    {
        $user     = new Collection();
        $response = new Collection();
        $data     = new Collection();

        if (!$request->has('email') || !$request->has('password')) {

            $response->put('errors', collect(["error" => __('Invalid email or password', 'cws-tools')]));

            return $response;
        }

        $user = wp_authenticate($request->get('email'), $request->get('password'));

        if (is_wp_error($user)) {

            $response->put('errors', collect(["error" => $user->get_error_message()]));

            return $response;
        }

        $_user = $this->getUser($user->ID);

        $data->put('id', $_user->get('ID'));
        $data->put('token', $_user->get('token'));
        $data->put('email', $_user->get('user_email'));
        $data->put('display_name', $_user->get('display_name'));
        $data->put('first_name', $_user->get('meta')->get('first_name'));
        $data->put('last_name', $_user->get('meta')->get('last_name'));
        $data->put('message', __('Successfull login', 'cws-tools'));
        $data->put('errors', collect());

        $response->put('data', $data);

        return $response;
    }

	public function setFields()
    {
        $fields     = new Collection();
        $fieldNames = ['id', 'email', 'username', 'password', 'confirm_password', 'first_name', 'last_name'];

        foreach ($fieldNames as $fieldName) {
            if ($this->vars->has($fieldName)) {
                $fields->put($fieldName, $this->vars->get($fieldName));
            }
        }

        return $fields;
    }

    public function validateUser($fields)
    {
        $errors = new Collection();

        if ($fields->has('id') && $fields->get('id') > 0) {
            if ($fields->has('email') && email_exists($fields->get('email')) > 0 && $fields->get('email') !== get_userdata($fields->get('id'))->user_email) {
                $errors->put('email', 'Email already exists');
            }
        } else {
            if (!$fields->has('first_name') || strlen($fields->get('first_name')) < 2) {
                $errors->put('first_name', __('First name must be at least 2 characters long', 'cws-tools'));
            }

            if (!$fields->has('last_name') || strlen($fields->get('last_name')) < 2) {
                $errors->put('last_name', __('Last name must be at least 2 characters long', 'cws-tools'));
            }

            if (!$fields->has('email') || !filter_var($fields->get('email'), FILTER_VALIDATE_EMAIL)) {
                $errors->put('email', __('Invalid email address', 'cws-tools'));
            } elseif (email_exists($fields->get('email')) > 0) {
                $errors->put('email', __('Email already exists', 'cws-tools'));
            }

            // validate password length
            if (!$fields->has('password') || strlen($fields->get('password')) < 6) {
                $errors->put('password', __('Password must be at least 6 characters long', 'cws-tools'));
            }

            // validate confirm password length
            if (!$fields->has('confirm_password') || strlen($fields->get('confirm_password')) < 6) {
                $errors->put('confirm_password', __('Confirm password must be at least 6 characters long', 'cws-tools'));
            }

            // validate password and confirm password match
            if (!$fields->has('password') || !$fields->has('confirm_password') ||
                $fields->get('password') !== $fields->get('confirm_password')) {
                $errors->put('password', __('Password and confirm password fields do not match', 'cws-tools'));
            }
        }

        return $errors;
    }

    public function createUserInWordpress($fields)
    {
        $userData = [
            'user_login' => $fields->get('email'),
            'user_email' => $fields->get('email'),
            'user_pass'  => $fields->get('password'),
            'first_name' => $fields->get('first_name', ''),
            'last_name'  => $fields->get('last_name', ''),
            'role'       => $fields->get('role', 'subscriber')
        ];

        $user_id = wp_insert_user($userData);

        if (is_wp_error($user_id)) {
            return false;
        }

        // add extra user meta
        $hashedpass = $fields->get('password');
        add_user_meta($user_id, 'hashedpass', base64_encode($hashedpass), true);

        $response = collect();
        $_user    = $this->getUser($user_id);

        $response->put('id', $_user->get('ID'));
        $response->put('token', $_user->get('token'));
        $response->put('email', $_user->get('user_email'));
        $response->put('display_name', $_user->get('display_name'));
        $response->put('first_name', $_user->get('meta')->get('first_name'));
        $response->put('last_name', $_user->get('meta')->get('last_name'));
        $response->put('message', __('Successfull registration', 'cws-tools'));
        $response->put('errors', collect());

        return $response;
    }

    /**
     * Return full list of user data
     *
     */
    public function getUser($userId): Collection
    {
        $user     = get_user_by('id', $userId);
        $userMeta = get_user_meta($userId);

        if (!$userMeta) {
            $userMeta = [];
        }

        $password           = base64_decode($userMeta['hashedpass'][0] ?? '');
        $simplifiedUserMeta = [];
        foreach ($userMeta as $key => $value) {
            if (!empty($value)) {
                $simplifiedUserMeta[$key] = maybe_unserialize($value[0]);
            }
        }

        $jwt_Auth_Public = new Jwt_Auth_Public($this->jwtAuth, $this->jwtAuthVersion);

        // Generate JWT token
        $wpRequest = new WP_REST_Request();
        $wpRequest->set_param('username', $user->user_email);
        $wpRequest->set_param('password', $password);
        $token = $jwt_Auth_Public->generate_token($wpRequest);

        if (isset($token->errors) && count($token->errors) > 0) {
            site_log($token->errors);

            return collect(['data' => collect([]), 'errors' => collect([
                'message' => __('Wrong data. Please reset your password.', 'cws-tools')
            ])]);
        }

        if (!is_wp_error($token) && isset($token['token'])) {
            $_user       = $user->data;
            $user->token = $token['token'];
        }

        $user->meta = collect($simplifiedUserMeta);

        return collect($_user);
    }

	/**
	 * Returns user's full name (First name, Last name)
	 * 
	 * @param [Integer] $user_id
	 * @return String 
	 */
	public function getUserFullName($user_id)
	{
		$first_name = get_user_meta($user_id, 'first_name', true);
		$last_name 	= get_user_meta($user_id, 'last_name', true);

		$name_array = [];

		if ($first_name) { $name_array[] = $first_name; }
		if ($last_name) { $name_array[] = $last_name; }

		return implode(' ', $name_array);
	}

    /**
 * Manage a user's wishlist by adding or removing post IDs
 * 
 * @param int    $user_id The user ID
 * @param int    $post_id The post ID to add or remove
 * @param string $mode    Action to perform: 'add' or 'delete'
 * @return bool  True on success, false on failure
 */
function manageUserWishlist($user_id, $post_id, $mode = 'add') {
    // Validate parameters
    if (!$user_id || !$post_id) {
        return false;
    }
    
    // Sanitize inputs
    $user_id = absint($user_id);
    $post_id = absint($post_id);
    $mode = in_array($mode, ['add', 'delete']) ? $mode : 'add';
    
    // Get current wishlist
    $wishlist = get_user_meta($user_id, 'wishlist', false);
    
    // Handle add mode
    if ($mode === 'add') {
        // Check if post is already in wishlist to avoid duplicates
        if (!in_array($post_id, $wishlist)) {
            return add_user_meta($user_id, 'wishlist', $post_id);
        }
        return true; // Already in wishlist
    }
    
    // Handle delete mode
    if ($mode === 'delete') {
        return delete_user_meta($user_id, 'wishlist', $post_id);
    }
    
    return false;
}
}
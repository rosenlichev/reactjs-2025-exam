<?php

/**
 * Abstract Custom Rest APIs Manager
 * 
 */
abstract class CwsAbstractBaseApiService
{
	protected string $namespace;

    protected string $version;

    public bool $debug = true;

	/**
     * Initialize the class and set its properties.
     *
     */
    public function __construct()
    {
    	$this->version   = 1;
        $this->namespace = 'cws-tools/v' . $this->version;

        // Register the rest_api_init action hook
        add_action('rest_api_init', [$this, 'add_api_routes']);
    }

    /**
     * Add the endpoints to the API
     */
    public function add_api_routes()
    {

    }

    /**
     * Returns the full REST API Url
     * 
     * @return String
     */
    public function getApiRouteUrl()
    {
        return get_rest_url() . $this->namespace;
    }

    /**
     * Send a JSON response to the client.
     */
    public function api_send_json($call, $data, $status = 200)
    {
        if ($this->debug) {
            site_log("user: " . get_current_user_id() . " , " . $call . ":   response " . ($status ?? 200) . " \n" . print_r($data, 1));
        }

        wp_send_json($data, $status ?? 200);
        die();
    }
}
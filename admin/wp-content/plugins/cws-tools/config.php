<?php

define("CWS_TOOLS_PLUGIN_DIR", dirname(__FILE__));
define("CWS_TOOLS_ASSETS_VERSION", rand(1000, 9000));
define("CWS_TOOLS_ASSETS", plugin_dir_url(__FILE__) . 'assets');
define("CWS_TOOLS_ABSPATH_ASSETS", plugin_dir_url(__FILE__) . '/assets');
define("CWS_TOOLS_ABSPATH_TEMPLATE", dirname(__FILE__) . '/templates');

// plugin: JWT Authentication for WP REST API
define('JWT_AUTH_SECRET_KEY', 'cws_tools_secret_key');

require dirname(__FILE__) . '/libs/vendor/autoload.php';

// Hooks & Functions
require_once dirname(__FILE__) . '/backend/functions.php';
require_once dirname(__FILE__) . '/backend/hooks.php';

require_once dirname(__FILE__) . '/frontend/functions.php';
require_once dirname(__FILE__) . '/frontend/hooks.php';

require_once dirname(__FILE__) . '/libs/constants.php';

$libs_dir = plugin_dir_path(__FILE__) . 'libs';

// Abstract Classes
foreach (glob($libs_dir . '/abstract/*.php') as $classFile) {
    require_once $classFile;
}

// Get all directories in the libs folder
$directories = array_filter(glob($libs_dir . '/*'), 'is_dir');

// Loop through each directory except 'abstract'
foreach ($directories as $directory) {
    $dir_name = basename($directory);
    
    // Skip the abstract directory as we've already loaded it
    if ($dir_name === 'abstract') {
        continue;
    }

    // Check if this directory has a libs subdirectory
    $libs_dir = $directory . '/libs';
    if (is_dir($libs_dir)) {
        // Load all PHP files from the classes subdirectory
        foreach (glob($libs_dir . '/*.php') as $file) {
            require_once $file;
        }
    }
    
    // Check if this directory has a classes subdirectory
    $classes_dir = $directory . '/libs/classes';
    if (is_dir($classes_dir)) {
        // Load all PHP files from the classes subdirectory
        foreach (glob($classes_dir . '/*.php') as $classFile) {
            require_once $classFile;
        }
    }

    // Check if this directory has a backend subdirectory
    $backend_dir = $directory . '/backend';
    if (is_dir($backend_dir)) {
        // Load all PHP files from the classes subdirectory
        foreach (glob($backend_dir . '/*.php') as $classFile) {
            require_once $classFile;
        }
    }

    // Check if this directory has a frontend subdirectory
    $frontend_dir = $directory . '/frontend';
    if (is_dir($frontend_dir)) {
        // Load all PHP files from the classes subdirectory
        foreach (glob($frontend_dir . '/*.php') as $classFile) {
            require_once $classFile;
        }
    }
}

if (!function_exists('__sd')) {
    /**
     * Site Debug , log function
     *
     * @param  [type] $url
     * @param  [type] $data
     * @return void
     */
    function __sd($data, $text = '')
    {
        if (!file_exists(ABSPATH . "/tmp")) {
            mkdir(ABSPATH . "/tmp", 0755, true);
        }

        file_put_contents(ABSPATH . "/tmp/request.txt", "\n ======== " .
            date("Y-m-d H:i:s", time()) . " ======== " . $text .
            "\n data: " . print_r($data, 1), FILE_APPEND);
    }
}

if (!function_exists('site_log')) {
    /**
     * Site Debug , log function
     *
     * @param  [type] $url
     * @param  [type] $data
     * @return void
     */
    function site_log($data, $text = '')
    {
        if (!file_exists(ABSPATH . "/tmp")) {
            mkdir(ABSPATH . "/tmp", 0755, true);
        }

        file_put_contents(ABSPATH . "/tmp/site_log.txt", "\n\n" .
            date("Y-m-d H:i:s", time()) . ": " . $text .
            "\n data: " . print_r($data, 1), FILE_APPEND);
    }
}

class CWS_ToolsConfig
{
    public function initPluginClasses()
    {
    	(new CwsRecipeApiRoutes);
        (new CwsRecipeService);

        (new CwsUserApiRoutes);
        (new CwsUserService);
    }

	public function admin()
	{

	}
}
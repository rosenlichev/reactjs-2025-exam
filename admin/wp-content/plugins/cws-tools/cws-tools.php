<?php

/*
 * Plugin Name: CWS Tools
 * Plugin URI: https://cobweb.biz
 * Description: CWS Tools plugin.
 * Version: 1.0.1 | by <a href="https://cobweb.biz" target="_blank">Cobweb Software</a>
 * Developer: Cobweb Software
 * Author URI: https://cobweb.biz
 */

require_once dirname(__FILE__) . '/config.php';

if (class_exists('CWS_ToolsConfig')) {

    $CWS_ToolsConfig = new CWS_ToolsConfig();

    $CWS_ToolsConfig->initPluginClasses();

    add_action('admin_menu', [$CWS_ToolsConfig, 'admin']);
}

function cws_tools_register_styles_and_scripts()
{
    // Styles and Scripts
}

add_action('wp_enqueue_scripts', 'cws_tools_register_styles_and_scripts');
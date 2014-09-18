<?php
/**
 * @package Gloime Auth
 * @version 0.1
 */
/*
Plugin Name: Glome Login
Plugin URI: http://wordpress.org/plugins/glome-login/
Description: todo
Author: somebody someone
Version: 0.1
Author URI: http://glome.me/
*/


function glome_login_activate ()
{
    if (!function_exists ('register_post_status'))
    {
        deactivate_plugins (basename (dirname (__FILE__)) . '/' . basename (__FILE__));
        echo sprintf (__ ('This plugin requires WordPress %s or newer. Please update your WordPress installation to activate this plugin.', 'oa_social_login'), '3.0');
        exit;
    }
    update_option ('glome_login_activation_message', 0);
}
register_activation_hook (__FILE__, 'glome_login_activate');


function glome_login_add_setup_link ($links, $file)
{
    static $glome_login_plugin = null;

    if (is_null ($glome_login_plugin))
    {
        $glome_login_plugin = plugin_basename (__FILE__);
    }

    if ($file == $glome_login_plugin)
    {
        $settings_link = '<a href="users.php?page=glome-settings">' . __ ('Setup', 'glome_login') . '</a>';
        array_unshift ($links, $settings_link);
    }
    return $links;
}
add_filter ('plugin_action_links', 'glome_login_add_setup_link', 10, 2);


function glome_settings()
{
    if (isset($_POST, $_POST['glome_login_settings'],
        $_POST['glome_login_settings']['api_uid'],
        $_POST['glome_login_settings']['api_domain'],
        $_POST['glome_login_settings']['api_key']))
    {
        update_option('glome_api_uid', $_POST['glome_login_settings']['api_uid']);
        update_option('glome_api_key', $_POST['glome_login_settings']['api_key']);
    }

    $domain = get_option('glome_api_domain');

    $settings = array(
        'api_domain' =>  empty($domain) ? 'https://stage.glome.me/api/v1/' : $domain ,
        'api_uid' => get_option('glome_api_uid'),
        'api_key' => get_option('glome_api_key'),
    );


    include __DIR__ . '/templates/settings.php';
}


function glome_login_admin_menu ()
{
    add_submenu_page('users.php' , 'Glome settings', 'Glome settings', 'manage_options', 'glome-settings', 'glome_settings');
}
add_action ('admin_menu', 'glome_login_admin_menu');


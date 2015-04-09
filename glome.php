<?php
/**
 * @package Glome plugin for Wordpress
 * @version 0.3
 */
/*
Plugin Name: Glome
Plugin URI: http://wordpress.org/plugins/glome/
Description: Glome plugin for Wordpress
Version: 0.3
Author: http://glome.me/
*/

include __DIR__ . '/includes/wp_bindings.php';
include __DIR__ . '/includes/glome_api.php';

function load_locales()
{
  $domain = 'glome_plugin';
  $locale = apply_filters('plugin_locale', get_locale(), $domain);
  // e.g. wp-content/plugins/plugin-name/languages/glome_plugin-en_US.mo
  load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/');
}
add_action('init', 'load_locales');

function glome_plugin_activate()
{
  if (! function_exists ('register_post_status'))
  {
    deactivate_plugins (basename(dirname(__FILE__)) . '/' . basename(__FILE__));
    exit;
  }
  update_option ('glome_plugin_activation_message', 0);
}
register_activation_hook (__FILE__, 'glome_plugin_activate');

function glome_plugin_add_setup_link ($links, $file)
{
  static $glome_plugin = null;

  if (is_null ($glome_plugin))
  {
    $glome_plugin = plugin_basename(__FILE__);
  }

  if ($file == $glome_plugin)
  {
    $settings_link = '<a href="users.php?page=glome-settings">' . _e('Setup', 'glome_plugin') . '</a>';
    array_unshift ($links, $settings_link);
  }
  return $links;
}
add_filter ('plugin_action_links', 'glome_plugin_add_setup_link', 10, 2);

function glome_settings()
{
  $email = null;
  $current_user = wp_get_current_user();

  if (isset($_POST, $_POST['method']) and $_POST['method'] == "new")
  {
    if (is_super_admin($current_user->ID))
    {
      $application = array(
        'username' => $current_user->user_login,
        'email' => $current_user->user_email,
        'servername' => $_SERVER['SERVER_NAME'],
        'requester' => 'Wordpress Plugin'
      );
      var_dump($application);
    }
  }
  else
  {
    if (isset($_POST, $_POST['glome_plugin_settings'],
      $_POST['glome_plugin_settings']['api_uid'],
      $_POST['glome_plugin_settings']['api_domain'],
      $_POST['glome_plugin_settings']['api_key']))
    {
      update_option('glome_api_uid', $_POST['glome_plugin_settings']['api_uid']);
      update_option('glome_api_key', $_POST['glome_plugin_settings']['api_key']);
      update_option('glome_api_domain', $_POST['glome_plugin_settings']['api_domain']);
    }

    $domain = get_option('glome_api_domain');

    $settings = array(
      'api_domain' =>  empty($domain) ? 'https://api.glome.me/' : $domain ,
      'api_uid' => get_option('glome_api_uid'),
      'api_key' => get_option('glome_api_key'),
    );

    $email = $current_user->email;
    include __DIR__ . '/templates/settings.php';
  }
}

function glome_plugin_admin_menu ()
{
  add_options_page(
    'Glome',
    'Glome',
    'manage_options',
    'glome_settings',
    'glome_settings'
  );
}
add_action ('admin_menu', 'glome_plugin_admin_menu');

function glome_start()
{
  $token = $glomeid = false;
  global $post;

  if (session_status() != PHP_SESSION_ACTIVE)
  {
    $_SESSION['glome'] = [];
    session_start();
  }

  if (isset($_POST['one_time_access']))
  {
    $_SESSION['glome'] = glome_create_user();
    $token = $_SESSION['glome']['token'];
    $glomeid = $_SESSION['glome']['glomeid'];
  }

  if (array_key_exists('magic', $_COOKIE) and strlen($_COOKIE['magic']) > 12)
  {
    // this is set after a succesful identification with Glome key
    if (array_key_exists('key', $_SESSION['glome']))
    {
      $key = substr($_COOKIE['magic'], 0, 12);

      if ($_SESSION['glome']['key']['code'] == $key)
      {
        $token = substr($_COOKIE['magic'], 12, 32);
        $glomeid = substr($_COOKIE['magic'], 44);
      }
    }
  }

  if ($token and $glomeid)
  {
    if (mywp_user_exists($token) === false)
    {
      mywp_create_user($token, $glomeid);
    }
    mywp_login_user($token, $glomeid);

    setcookie('magic', '', time() - 3600);  /* delete */
    header('Location: /');
  }

  // check Glome session
  $ret = glome_get_user_profile();

  if ($ret)
  {
    // is the Glome user locked?
    $_SESSION['glome'] = $ret;
    if (isset($ret['code']))
    {
      switch ($ret['code'])
      {
        case 403:
        case 2301:
          $ret = null;
          break;
      }
    }
  }

  if (is_user_logged_in() and $ret == null)
  {
    $current_user = wp_get_current_user();
    if (! is_super_admin($current_user->ID))
    {
      //logout from Wordpress
      wp_logout();
      header('Location: /');
      exit;
    }
  }

  glome_track_activity($_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
}
add_action('init', 'glome_start');

// called as soon as WP user is logged in
// TODO:
// this could be used to check if the WP account has a corresponding Glome ID
//
//add_action('set_current_user', 'glome_get_user_profile');

include __DIR__ . '/includes/ui.php';
include __DIR__ . '/includes/one_time_login_widget.php';
include __DIR__ . '/includes/key_widget.php';
include __DIR__ . '/includes/gnb_widget.php';
include __DIR__ . '/includes/pairing_widget.php';

<?php
/**
 * @package Glome Plugin for Wordpress
 * @version 1.5
 */
/*
Plugin Name: Glome
Plugin URI: http://wordpress.org/plugins/glome/
Description: Glome Plugin for Wordpress
Version: 1.5
Author: Glome Oy - http://glome.me
*/

include __DIR__ . '/includes/wp_bindings.php';
include __DIR__ . '/includes/glome_api.php';

/**
 *
 */
function load_locales()
{
  $domain = 'glome_plugin';
  $locale = apply_filters('plugin_locale', get_locale(), $domain);
  load_plugin_textdomain($domain, FALSE, plugin_basename(dirname(__FILE__)) . '/languages/');
}
add_action('init', 'load_locales');

/**
 *
 */
function glome_plugin_activate()
{
  add_option('glome_plugin_do_activation_redirect', true);

  if (! function_exists ('register_post_status'))
  {
    deactivate_plugins(basename(dirname(__FILE__)) . '/' . basename(__FILE__));
    exit;
  }
  update_option('glome_plugin_activation_message', 0);

  // check and set the Glome API domain
  // the function will sanitize the value; see glome_api.php
  $domain = get_api_domain();
}
register_activation_hook(__FILE__, 'glome_plugin_activate');

function glome_plugin_redirect()
{
  var_dump(get_option('glome_plugin_do_activation_redirect'));

  if (get_option('glome_plugin_do_activation_redirect', false))
  {
    delete_option('glome_plugin_do_activation_redirect');
    if( ! isset($_GET['activate-multi']))
    {
      wp_redirect('/wp-admin/options-general.php?page=glome_settings');
    }
  }
}
add_action('admin_init', 'glome_plugin_redirect');

/**
 *
 */
function glome_plugin_add_setup_link($links, $file)
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
add_filter('plugin_action_links', 'glome_plugin_add_setup_link', 10, 2);

/**
 * NOT IN USE
 *
 * Redirect user after successful login.
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $request URL the user is coming from.
 * @param object $user Logged user's data.
 * @return string
 */
function glome_login_redirect($to, $request, $user)
{
  $ret = $to;
  global $user;

  if (isset($user->roles) && is_array($user->roles) )
  {
    if (! $current_user->has_prop('glomeid'))
    {
      // no glome user -> go home
      $ret = home_url();
    }
  }
  return $ret;
}
//add_filter('login_redirect', 'glome_login_redirect', 10, 3);

include __DIR__ . '/includes/glome_plugin_admin_page.php';
include __DIR__ . '/includes/glome_plugin_profile_page.php';

/**
 *
 */
function glome_start()
{
  $token = $glomeid = $current_user = false;
  global $post;

  if (session_status() != PHP_SESSION_ACTIVE)
  {
    $_SESSION['glome'] = [];
    session_start();
  }

//$_SESSION['glome'] = [];

  // we don't save this into DB or file, no need to sanitze further
  if (isset($_POST['one_time_access']))
  {
    $_SESSION['glome'] = glome_create_user();
  }

  if (array_key_exists('glome', $_SESSION) and
      array_key_exists('token', $_SESSION['glome']) and
      array_key_exists('glomeid', $_SESSION['glome']))
  {
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

    redirect_if_needed();
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

  if (is_user_logged_in())
  {
    $current_user = wp_get_current_user();
  }

  if ($current_user && $ret == null)
  {
    if (! is_super_admin($current_user->ID))
    {
      //logout from Wordpress
      wp_logout();
      header('Location: /exit');
      exit;
    }
  }

  if (get_option('glome_activity_tracking'))
  {
    if ($current_user && $current_user->has_prop('glomeid') && $current_user->get('allow_tracking_me') == 1)
    {
      glome_track_activity($_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
      redirect_if_needed();
    }
  }
  return;
}
add_action('init', 'glome_start');

/**
 *
 */
function redirect_if_needed()
{
  // check if redirect is requested
  $url = '';

  if (isset($_COOKIE['redirect']))
  {
    $url = $_COOKIE['redirect'];
    setcookie('redirect', '', time() - 3600); /* delete */
  }
  else
  {
    if (isset($_GET['redirect_to']))
    {
      $url = $_GET['redirect_to'];
    }
  }

  if (strlen($url) > 1)
  {
    wp_safe_redirect(admin_url($url), 301);
    exit;
  }
}

// this could be used to check if the WP account has a corresponding Glome ID
// add_action('set_current_user', 'glome_get_user_profile');

include __DIR__ . '/includes/ui.php';
include __DIR__ . '/includes/pairing_common.php';
include __DIR__ . '/includes/one_time_login_widget.php';
include __DIR__ . '/includes/scanner_widget.php';
include __DIR__ . '/includes/show_qr_widget.php';
include __DIR__ . '/includes/gnb_widget.php';
include __DIR__ . '/includes/show_key_widget.php';
include __DIR__ . '/includes/enter_key_widget.php';

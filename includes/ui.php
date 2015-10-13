<?php

function render_gnb()
{

  include plugin_dir_path(__FILE__) . '../templates/gnb.php';
}

function render_one_time_login($source, $args = array ())
{
  include plugin_dir_path(__FILE__) . '../templates/one_time_login.php';
}

function render_key_login($source, $args = array ())
{
  include plugin_dir_path(__FILE__) . '../templates/key_login.php';
}

function render_scanner($source, $args = array ())
{
  include plugin_dir_path(__FILE__) . '../templates/scanner.php';
}

function render_show_qr($source, $args = array ())
{
  include plugin_dir_path(__FILE__) . '../templates/show_qr.php';
}

function render_enter_key($source, $args = array ())
{
  include plugin_dir_path(__FILE__) . '../templates/enter_key.php';
}

function glome_add_styles()
{
  $filepath = plugins_url('../assets/css/glome.css', __FILE__);
  wp_enqueue_style('glome', $filepath, false);
}
add_action('init', 'glome_add_styles');

function glome_add_scripts()
{
  $filepath = '/socket.io/socket.io.js';
  wp_enqueue_script('socketio', $filepath, false);

  $filepath = plugins_url('../assets/js/jquery.qrcode.min.js', __FILE__);
  wp_enqueue_script('jquery-qrlib', $filepath, array('jquery'));

  $filepath = plugins_url('../assets/js/jquery.mobile.custom.min.js', __FILE__);
  wp_enqueue_script('jquery-mob', $filepath, false);

  $filepath = plugins_url('../assets/js/qr_key.js', __FILE__);
  wp_enqueue_script('qr_key', $filepath, false);
  wp_localize_script('qr_key', 'key_params', array(
    'hello' => 'key'
  ));

  $filepath = plugins_url('../assets/js/llqrcode.js', __FILE__);
  wp_enqueue_script('jsqr', $filepath, false);

  $filepath = plugins_url('../assets/js/capture.js', __FILE__);
  wp_enqueue_script('capture', $filepath, false);

  $filepath = plugins_url('../assets/js/pairing.js', __FILE__);
  wp_enqueue_script('pairing', $filepath, false);
  wp_localize_script('pairing', 'pairing_params', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'pairing_url' => admin_url('admin-post.php?action=pairing&code='),
  ));

  $filepath = plugins_url('../assets/js/unpairing.js', __FILE__);
  wp_enqueue_script('unpairing', $filepath, false);
  wp_localize_script('unpairing', 'unpairing_params', array(
    'ajax_url' => admin_url('admin-ajax.php')
  ));

  $filepath = plugins_url('../assets/js/key_authentication.js', __FILE__);
  wp_enqueue_script('key_auth', $filepath, false);
  wp_localize_script('key_auth', 'key_auth_params', array(
    'ajax_url' => admin_url('admin-ajax.php')
  ));

  $filepath = plugins_url('../assets/js/jquery.cookie.js', __FILE__);
  wp_enqueue_script('jquerycookie', $filepath, false);

  $filepath = plugins_url('../assets/js/gnb.js', __FILE__);
  wp_enqueue_script('gnb', $filepath, false);

  $token = false;
  if (is_user_logged_in())
  {
    $current_user = wp_get_current_user();
    $token = $current_user->user_login;
  }
  wp_localize_script('gnb', 'gnb_params', array(
    'uid' => get_option('glome_api_uid'),
    'gid' => session_id(),
    'token' => $token
  ));
}
add_action('wp_enqueue_scripts', 'glome_add_scripts');

function glome_logout()
{
  if (isset($_SESSION['glome']))
  {
    unset($_SESSION['glome']);
    #header('Location: /');
    #exit;
  }
}
add_action('wp_logout', 'glome_logout');

function custom_logout_url($default)
{
  $url = esc_html(site_url('logout/' . wp_create_nonce('log-out'), 'logout'));
  return $url;
}
add_filter('logout_url', 'custom_logout_url');

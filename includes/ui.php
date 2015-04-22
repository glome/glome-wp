<?php

function render_gnb()
{
  include __DIR__ . '/../templates/gnb.php';
}

function render_one_time_login($source, $args = array ())
{
  include __DIR__ . '/../templates/one_time_login.php';
}

function render_key_login($source, $args = array ())
{
  include __DIR__ . '/../templates/key_login.php';
}

function glome_add_styles()
{
  $filepath = plugins_url('/glome-wp/assets/css/glome.css');
  wp_enqueue_style('glome', $filepath, false);

  $filepath = plugins_url('/glome-wp/assets/css/flipclock.css');
  wp_enqueue_style('flipclock', $filepath, false);
}
add_action('init', 'glome_add_styles');

function glome_add_scripts()
{
  $filepath = '/socket.io/socket.io.js';
  wp_enqueue_script('socketio', $filepath, false);

  $filepath = plugins_url('/glome-wp/assets/js/jquery.qrcode.min.js');
  wp_enqueue_script('jquery-qrlib', $filepath, array('jquery'));

  $filepath = plugins_url('/glome-wp/assets/js/qr_key.js');
  wp_enqueue_script('qr_key', $filepath, false);
  wp_localize_script('qr_key', 'key_params', array(
    'hello' => 'key'
  ));

  $filepath = plugins_url('/glome-wp/assets/js/llqrcode.js');
  wp_enqueue_script('jsqr', $filepath, false);

  $filepath = plugins_url('/glome-wp/assets/js/capture.js');
  wp_enqueue_script('capture', $filepath, false);

  $filepath = plugins_url('/glome-wp/assets/js/pairing.js');
  wp_enqueue_script('pairing', $filepath, false);
  wp_localize_script('pairing', 'pairing_params', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'pairing_url' => admin_url('admin-post.php?action=pairing&code='),
  ));

  $filepath = plugins_url('/glome-wp/assets/js/unpairing.js');
  wp_enqueue_script('unpairing', $filepath, false);
  wp_localize_script('unpairing', 'unpairing_params', array(
    'ajax_url' => admin_url('admin-ajax.php')
  ));

  $filepath = plugins_url('/glome-wp/assets/js/jquery.cookie.js');
  wp_enqueue_script('jquerycookie', $filepath, false);

  $filepath = plugins_url('/glome-wp/assets/js/gnb.js');
  wp_enqueue_script('gnb', $filepath, false);

  //~ $filepath = plugins_url('/glome-wp/assets/js/flipclock.min.js');
  //~ wp_enqueue_script('flipclock', $filepath, false);

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
  $url = esc_html(site_url('logout/' . wp_create_nonce('log-out'), 'login'));
  return $url;
}
add_filter('logout_url', 'custom_logout_url');

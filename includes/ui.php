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

function render_pair($source, $args = array ())
{
  include __DIR__ . '/../templates/pair.php';
}

function glome_add_styles()
{
  $filepath = plugins_url('/glome-wp/assets/css/glome.css');
  wp_enqueue_style('glome_styles', $filepath, false);
}
add_action( 'wp_enqueue_scripts', 'glome_add_styles');

function glome_add_scripts()
{
  $filepath = plugins_url('/glome-wp/assets/js/jquery.qrcode.min.js');
  wp_enqueue_script('jquery-qrlib', $filepath, array('jquery'));

  $filepath = plugins_url('/glome-wp/assets/js/qr.js');
  wp_enqueue_script('qr', $filepath, false);

  $filepath = '/socket.io/socket.io.js';
  wp_enqueue_script('socketio', $filepath, false);

  $filepath = plugins_url('/glome-wp/assets/js/jquery.cookie.js');
  wp_enqueue_script('jquerycookie', $filepath, false);

  $filepath = plugins_url('/glome-wp/assets/js/gnb.js');
  wp_enqueue_script('gnb', $filepath, false);

  $token = false;
  if (is_user_logged_in())
  {
    $current_user = wp_get_current_user();
    $token = $current_user->user_login;
  }
  wp_localize_script( 'gnb', 'gnb_params', array(
    'uid' => get_option('glome_api_uid'),
    'gid' => session_id(),
    'token' => $token
  ));
}
add_action( 'wp_enqueue_scripts', 'glome_add_scripts');

function glome_ajax_challenge()
{
  if (glome_is_session_paired())
  {
    $id = $_SESSION['glome']['id'];
    glome_plugin_user($id);
    echo 1;
    exit;
  }
  $code = glome_get_pairing_code();
  echo json_encode(str_split($code, 4));

  exit;
}
add_action( 'wp_ajax_challenge', 'glome_ajax_challenge' );

function glome_ajax_verify()
{
  $code = glome_is_session_paired();

  if ($code === true)
  {
    echo 1;
  }
  else
  {
    echo 0;
  }

  exit;
}
add_action( 'wp_ajax_verify', 'glome_ajax_verify' );

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
  $url = esc_html(site_url('logout/'.wp_create_nonce('log-out'), 'login'));
  return $url;
}
add_filter( 'logout_url', 'custom_logout_url');

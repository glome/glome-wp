<?php
// handler for creating pairing code from JS
function glome_ajax_create_pairing_code()
{
  echo glome_create_pairing_code($_POST['kind']);
  die();
}
add_action('wp_ajax_create_pairing_code', 'glome_ajax_create_pairing_code');

// handler for unpairing devices from JS
function glome_ajax_unpair()
{
  echo glome_post_unpair($_POST['id']);
  die();
}
add_action('wp_ajax_unpair', 'glome_ajax_unpair');

// handler for GETing pairing codes
function handle_pairing()
{
    $url = '/';
    $sync_code = false;
    status_header(200);

    if (isset($_REQUEST['code']) && strlen($_REQUEST['code']) == 12)
    {
      $sync_code = $_REQUEST['code'];
    }

    if ($sync_code)
    {
      if (is_user_logged_in())
      {
        $current_user = wp_get_current_user();
        if ($current_user->has_prop('glomeid'))
        {
          $ret = glome_post_pairing_code($sync_code);
          (isset($ret['code'])) ? $code = $ret['code'] : $code = 200;
          $url = admin_url('profile.php?page=glome_profile&action=pairing&code=' . $code);
        }
      }
      else
      {
        $page = urlencode('admin-post.php?action=pairing&code=' . $sync_code);
        $url .= '?redirect_to=' . $page;
        setcookie('redirect', $page);
      }
    }

    wp_safe_redirect($url, 301);
}
add_action('admin_post_pairing', 'handle_pairing');
add_action('admin_post_nopriv_pairing', 'handle_pairing');
?>

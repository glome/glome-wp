<?php
// handler for creating pairing code from JS
function glome_ajax_create_pairing_code()
{
  // validate kind
  // 1. allowed values: b
  if ($_POST['kind'] == 'b')
  {
    echo glome_create_pairing_code($_POST['kind']);
  }
  die();
}
add_action('wp_ajax_create_pairing_code', 'glome_ajax_create_pairing_code');

// handler for unpairing devices from JS
function glome_ajax_unpair()
{
  // validate the id
  // 1. must be an integer > 0
  if ((int)$_POST['id'] > 0)
  {
    echo glome_post_unpair($_POST['id']);
  }
  die();
}
add_action('wp_ajax_unpair', 'glome_ajax_unpair');

// handler for GETing pairing codes
function handle_pairing()
{
    $url = '/';
    $sync_code = false;
    status_header(200);

    // validate the code variable
    if (isset($_REQUEST['code']) && strlen($_REQUEST['code']) == 12 &&
        ctype_xdigit($_REQUEST['code']))
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

          if ($code == 200 and get_option('glome_clone_name'))
          {
            // pairing was OK so lookup WP user based on paired Glome ID
            $wp_source = get_users(array(
              'meta_key' => 'glomeid',
              'meta_value' => $ret['pair']['glomeid'],
              'number' => 1,
              'count_total' => false
            ));

            if (count($wp_source))
            {
              $user_info = get_userdata($wp_source[0]->ID);

              if ($user_info->display_name != "Anonymous")
              {
                // clone stuff
                $data = [
                  'ID' => $current_user->ID,
                  'first_name' => $user_info->first_name,
                  'last_name' => $user_info->last_name,
                  'display_name' => $user_info->display_name
                ];
                wp_update_user($data);
              }
            }
          }

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

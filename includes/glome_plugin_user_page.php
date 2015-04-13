<?php
/**
 * Glome user profile page
 */
function glome_profile_page()
{
  $current_user = wp_get_current_user();

  // check Glome API access and display the menu only for
  // Glome enabled accounts
  if (! $current_user->has_prop('glomeid'))
  {
    return;
  }

  function glome_user_page_content()
  {
    include __DIR__ . '/../templates/glome_profile.php';
  }

  add_users_page(
    'Glome',
    'Your Glome Profile',
    'read',
    'glome_profile',
    'glome_profile_page_content'
  );
}
add_action('admin_menu', 'glome_profile_page');

// handler for creating pairing code from JS
function glome_ajax_create_pairing_code()
{
  echo glome_create_pairing_code($_POST['kind']);
  die();
}
add_action('wp_ajax_create_pairing_code', 'glome_ajax_create_pairing_code');

// handler for GETing pairing codes
function handle_pairing()
{
    status_header(200);
    if (isset($_REQUEST['code']))
    {
      $code = $_REQUEST['code'];
      die($code);
    }
}
add_action('admin_post_pairing', 'handle_pairing');
add_action('admin_post_nopriv_pairing', 'handle_pairing');
?>
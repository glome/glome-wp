<?php
/**
 * Glome user profile page
 */
function glome_profile_page()
{
  // check Glome API access and display the menu only for
  // Glome enabled accounts
  $current_user = wp_get_current_user();
  if (! $current_user->has_prop('glomeid'))
  {
    return;
  }

  if (isset($_POST, $_POST['glome_plugin_settings']))
  {
    $checkbox = (int)(isset($_POST['glome_plugin_settings']['allow_tracking_me']));
    update_user_meta($current_user->ID, 'allow_tracking_me', $checkbox);
  }

  /* load the JS */
  add_action('admin_enqueue_scripts', 'glome_add_scripts');

  function glome_profile_page_content()
  {
    $current_user = wp_get_current_user();
    $pairs = glome_get_brothers();
    $settings = array(
      'allow_tracking_me' => $current_user->get('allow_tracking_me')
    );
    include plugin_dir_path(__FILE__) . '../templates/glome_profile.php';
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
?>
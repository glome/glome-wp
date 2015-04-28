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

  /* load the JS */
  add_action('admin_enqueue_scripts', 'glome_add_scripts');

  function glome_profile_page_content()
  {
    $current_user = wp_get_current_user();
    $pairs = glome_get_brothers();
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
?>
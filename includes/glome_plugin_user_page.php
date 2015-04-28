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
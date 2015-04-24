<?php
/**
 * Glome plugin admin page
 */
function glome_plugin_admin_page()
{
  /**
   *
   */
  function glome_settings()
  {
    $email = null;
    $current_user = wp_get_current_user();

    if (isset($_POST, $_POST['method']) and $_POST['method'] == "new")
    {
      if (is_super_admin($current_user->ID))
      {
        $application = array(
          'username' => $current_user->user_login,
          'email' => $current_user->user_email,
          'servername' => $_SERVER['SERVER_NAME'],
          'requester' => 'Wordpress Plugin'
        );
        var_dump($application);
      }
    }
    else
    {
      if (isset($_POST, $_POST['glome_plugin_settings'],
        $_POST['glome_plugin_settings']['api_uid'],
        $_POST['glome_plugin_settings']['api_domain'],
        $_POST['glome_plugin_settings']['api_key']))
      {
        update_option('glome_api_uid', $_POST['glome_plugin_settings']['api_uid']);
        update_option('glome_api_key', $_POST['glome_plugin_settings']['api_key']);
        update_option('glome_api_domain', $_POST['glome_plugin_settings']['api_domain']);
        $checkbox = (int)(isset($_POST['glome_plugin_settings']['activity_tracking']));
        update_option('glome_activity_tracking', $checkbox);
      }

      $domain = get_option('glome_api_domain');

      $settings = array(
        'api_domain' =>  empty($domain) ? 'https://api.glome.me/' : $domain ,
        'api_uid' => get_option('glome_api_uid'),
        'api_key' => get_option('glome_api_key'),
        'activity_tracking' => get_option('glome_activity_tracking')
      );

      $email = $current_user->email;
      include __DIR__ . '/../templates/settings.php';
    }
  }

  add_options_page(
    'Glome',
    'Glome',
    'manage_options',
    'glome_settings',
    'glome_settings'
  );
}
add_action ('admin_menu', 'glome_plugin_admin_page');
?>
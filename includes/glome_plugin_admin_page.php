<?php
/**
 * Glome plugin admin page
 */
function glome_plugin_admin_page()
{
  function glome_add_admin_scripts()
  {
    $filepath = plugins_url('../assets/js/request_api_access.js', __FILE__);
    wp_enqueue_script('apiaccess', $filepath, false);
    wp_localize_script('apiaccess', 'api_access_params', array(
      'ajax_url' => admin_url('admin-ajax.php') . '?action=request_api_access',
    ));
  }

  /* load the JS */
  add_action('admin_enqueue_scripts', 'glome_add_admin_scripts');

  /**
   *
   */
  function glome_settings()
  {
    $email = null;
    $domain = null;
    $current_user = wp_get_current_user();

    if (isset($_POST, $_POST['glome_plugin_settings'],
      $_POST['glome_plugin_settings']['api_uid'],
      $_POST['glome_plugin_settings']['api_domain'],
      $_POST['glome_plugin_settings']['api_key']))
    {
      // sanitize and validate api_domain
      // 1. it must be a valid URL
      // 2. length: max 255 bytes
      $raw = esc_url_raw($_POST['glome_plugin_settings']['api_domain']);

      $sanitized = filter_var($raw, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
      if ($sanitized !== FALSE && strlen($sanitized) <= 255)
      {
        $domain = $sanitized;
        update_option('glome_api_domain', $sanitized);
      }
      else
      {
        $domain = get_api_domain();
      }

      // sanitize and validate api_uid
      // 1. it's a string (a-zA-Z0-9.) chars allowed
      // 2. length: max 255 bytes
      $raw = $_POST['glome_plugin_settings']['api_uid'];
      $sanitized = preg_replace('/[^a-zA-Z0-9.]/i', '', $raw);
      if (strlen($sanitized) <= 255)
      {
        update_option('glome_api_uid', $sanitized);
      }

      // sanitize and validate api_key
      // 1. only hex string
      // 2. 32 bytes fixed length
      $raw = $_POST['glome_plugin_settings']['api_key'];
      $sanitized = preg_replace( '/[^a-fA-F0-9]/i', '', $raw);
      if (ctype_xdigit($sanitized) && strlen($sanitized) == 32)
      {
        update_option('glome_api_key', $sanitized);
      }

      // validate activity_tracking
      // can only be 0 or 1
      $checkbox = (int)(isset($_POST['glome_plugin_settings']['activity_tracking']));
      update_option('glome_activity_tracking', $checkbox);

      // validate clone_name
      // can only be 0 or 1
      $checkbox = (int)(isset($_POST['glome_plugin_settings']['clone_name']));
      update_option('glome_clone_name', $checkbox);
    }

    $domain = get_option('glome_api_domain');

    $settings = array(
      'api_domain' => empty($domain) ? get_api_domain() : $domain,
      'api_uid' => get_option('glome_api_uid'),
      'api_key' => get_option('glome_api_key'),
      'activity_tracking' => get_option('glome_activity_tracking'),
      'clone_name' => get_option('glome_clone_name')
    );
    $email = $current_user->email;
    include __DIR__ . '/../templates/settings.php';
  }

  add_options_page(
    'Glome',
    'Glome',
    'manage_options',
    'glome_settings',
    'glome_settings'
  );
}
add_action('admin_menu', 'glome_plugin_admin_page');

/**
 * AJAX handler for requesting Glome API access
 */
function glome_ajax_request_api_access()
{
  $ret = [];
  $current_user = wp_get_current_user();

  if (is_super_admin($current_user->ID))
  {
    $application = array(
      'username' => $current_user->user_login,
      'email' => $current_user->user_email,
      'servername' => $_SERVER['SERVER_NAME'],
      'requester' => 'Glome Plugin for Wordpress'
    );

    $ret = glome_request_api_access($application);

    if ($ret)
    {
      $json = json_decode($ret);

      if ($json && ! property_exists($json, 'error'))
      {
        // save the API credentials to the local WP instance
        update_option('glome_api_uid', $json->uid);
        update_option('glome_api_key', $json->apikey);
      }
    }

    echo $ret;
  }

  die();
}
add_action('wp_ajax_request_api_access', 'glome_ajax_request_api_access');

?>
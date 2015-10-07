<?php
/**
 * Glome Enter Key Widget
 */
class glome_enter_key_widget extends WP_Widget
{
  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct('glome_enter_key', 'Enter Glome Key', array(
      'description' => __('Allow your users to pair up using a key code.', 'glome_plugin')
    ));
  }

  /**
   *  Display the widget
   */
  public function widget($args, $instance)
  {
    //Hide the widget for not logged in visitors?
    if (! empty($instance['widget_hide_for_visitors'])) return;

    // check api access
    if (glome_check_app() == false)
    {
      return;
    }

    //Before Widget
    echo $args['before_widget'];

    //Title
    if (! empty ($instance['widget_title']))
    {
      echo $args['before_title'] . apply_filters('widget_title', $instance['widget_title']) . $args['after_title'];
    }

    //Content
    echo render_enter_key('widget', $instance);

    //After Widget
    echo $args['after_widget'];
  }

  /**
   * Show Widget Settings
   */
  public function form($instance)
  {
    //Default settings
    $default_settings = array(
      'widget_title' => __('Enter Key Widget Title', 'glome_plugin'),
      'widget_hide_for_logged_in_users' => '1',
      'widget_hide_for_visitors' => '0'
    );

    $instance = wp_parse_args((array) $instance, $default_settings);
    ?>
      <p>
        <label for="<?php echo $this->get_field_id ('widget_title'); ?>"><?php _e('Scanner Widget Title', 'glome_plugin'); ?>:</label>
        <input class="widefat" id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" value="<?php echo $instance ['widget_title']; ?>" />
      </p>
      <p>
        <input type="checkbox" id="<?php echo $this->get_field_id('widget_hide_for_logged_in_users', 'glome_scanner_widget'); ?>" name="<?php echo $this->get_field_name('widget_hide_for_logged_in_users'); ?>" type="text" value="1" <?php echo (!empty ($instance ['widget_hide_for_logged_in_users']) ? 'checked="checked"' : ''); ?> />
        <label for="<?php echo $this->get_field_id('widget_hide_for_logged_in_users'); ?>"><?php _e('Tick to hide the widget for logged-in users', 'glome_plugin'); ?></label>
      </p>
      <p>
        <input type="checkbox" id="<?php echo $this->get_field_id('widget_hide_for_visitors', 'glome_scanner_widget'); ?>" name="<?php echo $this->get_field_name('widget_hide_for_visitors'); ?>" type="text" value="1" <?php echo (!empty ($instance ['widget_hide_for_visitors']) ? 'checked="checked"' : ''); ?> />
        <label for="<?php echo $this->get_field_id('widget_hide_for_visitors'); ?>"><?php _e('Tick to hide the widget for visitors', 'glome_plugin'); ?></label>
      </p>
    <?php
  }

  /**
   * Update Widget Settings
   */
  public function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['widget_title'] = trim(strip_tags($new_instance['widget_title']));
    $instance['widget_hide_for_logged_in_users'] = (empty($new_instance['widget_hide_for_logged_in_users']) ? 0 : 1);
    $instance['widget_hide_for_visitors'] = (empty($new_instance['widget_hide_for_visitors']) ? 0 : 1);
    return $instance;
  }
}
add_action ('widgets_init', create_function('', 'return register_widget("glome_enter_key_widget");'));

/**
 * AJAX handler for key authentication
 */
function glome_ajax_authenticate_with_key()
{
  $ret = glome_post_key_code($_POST['code_part_1'], $_POST['code_part_2'], $_POST['code_part_3']);
  $json = json_decode($ret);

  if (! property_exists($json, 'error'))
  {
    // create a proper WP account and login the user
    if (property_exists($json, 'user'))
    {
      $_SESSION['glome'] = [
        'token' => $json->user->token,
        'glomeid' => $json->user->glomeid
      ];
    }

    //~ $token = $json->user->token || false;
    //~ $glomeid = $json->user->glomeid || false;

    //~ if ($token and $glomeid)
    //~ {
      //~ if (mywp_user_exists($token) === false)
      //~ {
        //~ mywp_create_user($token, $glomeid);
      //~ }
      //~ mywp_login_user($token, $glomeid);

      //~ setcookie('magic', '', time() - 3600);  /* delete */

      //~ redirect_if_needed();
    //~ }
  }

  echo $ret;

  die();
}
add_action('wp_ajax_nopriv_authenticate_with_key', 'glome_ajax_authenticate_with_key');

?>
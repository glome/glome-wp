<?php
/**
 * Glome Key Widget
 */
class glome_key_widget extends WP_Widget
{
  /**
   * Constructor
   */
  public function __construct()
  {
    parent::WP_Widget('glome_key', 'Glome Key Login', array(
      'description' => __('Allow your visitors to login with Glome Key.', 'glome_plugin')
    ));
  }

  /**
   *  Display the widget
   */
  public function widget($args, $instance)
  {
    // check api access
    if (glome_check_app() == false)
    {
      return;
    }

    // request Glome key
    if (isset($_SESSION['glome']) === false)
    {
      $_SESSION['glome'] = array();
    }
    if (! isset($_SESSION['glome']['glomeid']))
    {
      $instance['key'] = glome_get_key();
    }

    //Hide the widget for logged in users?
    if (isset($instance['key']['code']) and
        (empty ($instance ['widget_hide_for_logged_in_users']) or ! is_user_logged_in ()))
    {
      //Before Widget
      echo $args ['before_widget'];

      //Title
      if (! empty ($instance ['widget_title']))
      {
        echo $args ['before_title'] . apply_filters ('widget_title', $instance ['widget_title']) . $args ['after_title'];
      }

      //Content
      echo render_key_login('widget', $instance);

      //After Widget
      echo $args ['after_widget'];
    }
  }

  /**
   * Show Widget Settings
   */
  public function form($instance)
  {
    //Default settings
    $default_settings = array(
      'widget_title' => __('Key Widget Title', 'glome_plugin'),
      'widget_hide_for_logged_in_users' => '1'
    );

    $instance = wp_parse_args ((array) $instance, $default_settings);
    ?>
      <p>
        <label for="<?php echo $this->get_field_id ('widget_title'); ?>"><?php _e('Key Widget Title', 'glome_plugin'); ?>:</label>
        <input class="widefat" id="<?php echo $this->get_field_id ('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" value="<?php echo $instance ['widget_title']; ?>" />
      </p>
      <p>
        <input type="checkbox" id="<?php echo $this->get_field_id ('widget_hide_for_logged_in_users', 'glome_key_widget'); ?>" name="<?php echo $this->get_field_name('widget_hide_for_logged_in_users'); ?>" type="text" value="1" <?php echo (!empty ($instance ['widget_hide_for_logged_in_users']) ? 'checked="checked"' : ''); ?> />
        <label for="<?php echo $this->get_field_id ('widget_hide_for_logged_in_users'); ?>"><?php _e('Tick to hide key widget for logged-in users', 'glome_plugin'); ?></label>
      </p>
    <?php
  }

  /**
   * Update Widget Settings
   */
  public function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance ['widget_title'] = trim(strip_tags($new_instance['widget_title']));
    $instance ['widget_hide_for_logged_in_users'] = (empty ($new_instance['widget_hide_for_logged_in_users']) ? 0 : 1);
    return $instance;
  }
}
add_action ('widgets_init', create_function('', 'return register_widget("glome_key_widget");'));
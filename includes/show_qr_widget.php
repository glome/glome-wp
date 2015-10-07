<?php
/**
 * Glome Show QR Widget
 */
class glome_show_qr_widget extends WP_Widget
{
  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct('glome_show_qr_widget', 'Glome QR', array(
      'description' => __('Show a QR code for pairing.', 'glome_plugin')
    ));
  }

  /**
   *  Display the widget
   */
  public function widget($args, $instance)
  {
    if (! is_user_logged_in()) return;

    // check api access
    if (glome_check_app() == false)
    {
      return;
    }

    //Before Widget
    echo $args ['before_widget'];

    //Title
    if (! empty ($instance ['widget_title']))
    {
      echo $args ['before_title'] . apply_filters ('widget_title', $instance ['widget_title']) . $args ['after_title'];
    }

    //Content
    echo render_show_qr('widget', $instance);

    //After Widget
    echo $args ['after_widget'];
  }

  /**
   * Show Widget Settings
   */
  public function form($instance)
  {
    //Default settings
    $default_settings = array(
      'widget_title' => __('Show QR Widget Title', 'glome_plugin'),
      'widget_hide_for_logged_in_users' => '0'
    );

    $instance = wp_parse_args((array) $instance, $default_settings);
    ?>
      <p>
        <label for="<?php echo $this->get_field_id('widget_title'); ?>"><?php _e('Show QR Widget Title', 'glome_plugin'); ?>:</label>
        <input class="widefat" id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" value="<?php echo $instance ['widget_title']; ?>" />
      </p>
      <p>
        <input type="checkbox" id="<?php echo $this->get_field_id('widget_hide_for_logged_in_users', 'glome_qr_for_pairing_widget'); ?>" name="<?php echo $this->get_field_name('widget_hide_for_logged_in_users'); ?>" type="text" value="1" <?php echo (!empty ($instance ['widget_hide_for_logged_in_users']) ? 'checked="checked"' : ''); ?> />
        <label for="<?php echo $this->get_field_id('widget_hide_for_logged_in_users'); ?>"><?php _e('Tick to hide the widget for logged-in users', 'glome_plugin'); ?></label>
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
    return $instance;
  }
}
add_action ('widgets_init', create_function('', 'return register_widget("glome_show_qr_widget");'));

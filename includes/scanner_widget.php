<?php
/**
 * Glome Scanner Widget
 */
class glome_scanner_widget extends WP_Widget
{
  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct('glome_scanner', 'Glome Scanner Pairing', array(
      'description' => __('Allow your users to pair up using QR code and a scanner.', 'glome_plugin')
    ));
  }

  /**
   *  Display the widget
   */
  public function widget($args, $instance)
  {
    //Hide the widget for not logged in visitors?
    if (! empty($instance['widget_hide_for_visitors']) && ! is_user_logged_in()) return;

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
    echo render_scanner('widget', $instance);

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
      'widget_title' => __('Scanner Widget Title', 'glome_plugin'),
      'widget_hide_for_logged_in_users' => '0',
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
add_action ('widgets_init', create_function('', 'return register_widget("glome_scanner_widget");'));

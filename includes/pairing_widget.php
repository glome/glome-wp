<?php
/**
 * Glome Pairing Widget
 */
class glome_pairing_widget extends WP_Widget
{
  /**
   * Constructor
   */
  public function __construct()
  {
    parent::WP_Widget('pairing', 'Glome Pairing Widget', array(
      'description' => __('Pair with other devices', 'glome_plugin')
    ));
  }

  /**
   *  Display the widget
   */
  public function widget($args, $instance)
  {
    // check api access
    if (glome_check_app() == false or ! is_user_logged_in())
    {
      return;
    }

    //Before Widget
    echo $args['before_widget'];

    //Title
    if (! empty($instance ['widget_title']))
    {
      echo $args['before_title'] . apply_filters('widget_title', $instance ['widget_title']) . $args ['after_title'];
    }

    //Content
    echo render_pairing('widget', $instance);
  }

  /**
   * Show Widget Settings
   */
  public function form($instance)
  {
    //Default settings
    $default_settings = array(
      'widget_title' => __('Pairing Widget Title', 'glome_plugin'),
      'widget_hide_for_logged_in_users' => '0'
    );

    $instance = wp_parse_args((array) $instance, $default_settings);
    ?>
      <p>
        <label for="<?php echo $this->get_field_id ('widget_title'); ?>"><?php _e('Pairing Widget Title', 'glome_plugin'); ?>:</label>
        <input class="widefat" id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" type="text" value="<?php echo $instance ['widget_title']; ?>" />
      </p>
      <p>
        <input type="checkbox" id="<?php echo $this->get_field_id('widget_hide_for_logged_in_users', 'glome_pairing_widget'); ?>" name="<?php echo $this->get_field_name('widget_hide_for_logged_in_users'); ?>" type="text" value="1" <?php echo (!empty ($instance ['widget_hide_for_logged_in_users']) ? 'checked="checked"' : ''); ?> />
        <label for="<?php echo $this->get_field_id ('widget_hide_for_logged_in_users'); ?>"><?php _e('Tick to hide pairing widget for logged-in users', 'glome_plugin'); ?></label>
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
    $instance ['widget_hide_for_logged_in_users'] = (empty($new_instance ['widget_hide_for_logged_in_users']) ? 0 : 1);
    return $instance;
  }
}
add_action ('widgets_init', create_function ('', 'return register_widget("glome_pairing_widget" );'));

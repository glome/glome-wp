<?php
/**
 * Glome Login Widget
 */
class gnb_widget extends WP_Widget
{
  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct('gnb', 'Glome Notification Broker', array(
      'description' => __('GNB widget description', 'gnb_widget')
    ));
  }

  /**
   *  Display the widget
   */
  public function widget($args, $instance)
  {
    //Content
    echo render_gnb('widget', $instance);
  }
}
add_action('widgets_init', create_function('', 'return register_widget("gnb_widget");'));
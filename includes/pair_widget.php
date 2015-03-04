<?php
/**
 * Glome Login Widget
 */
class pair_widget extends WP_Widget
{
  /**
   * Constructor
   */
  public function __construct()
  {
    parent::WP_Widget('pair', 'Glome pair widget', array(
      'description' => __('Pair widget description', 'glome_plugin')
    ));
  }

  /**
   *  Display the widget
   */
  public function widget($args, $instance)
  {
    //Content
    echo render_pair('widget', $instance);
  }
}
add_action ('widgets_init', create_function ('', 'return register_widget( "pair_widget" );'));
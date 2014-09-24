<?php

/**
 * Glome Login Widget
 */
class glome_login_widget extends WP_Widget
{
    /**
     * Constructor
     */
    public function __construct ()
    {
        parent::WP_Widget ('glome_login', 'Glome Login', array (
            'description' => __ ('Allow your visitors to login with anonymous Glome Wallet account.', 'glome_login')
        ));
    }

    /**
     *  Display the widget
     */
    public function widget ($args, $instance)
    {

        if (isset($_SESSION['glome']) === false) {
            $_SESSION['glome'] = array();
        }

        if (isset($_SESSION['glome']['session']) === false) {
            $_SESSION['glome']['session'] = get_glome_session_id();
        }

        //Hide the widget for logged in users?
        if (empty ($instance ['widget_hide_for_logged_in_users']) OR !is_user_logged_in ())
        {
            //Before Widget
            echo $args ['before_widget'];

            //Title
            if (!empty ($instance ['widget_title']))
            {
                echo $args ['before_title'] . apply_filters ('widget_title', $instance ['widget_title']) . $args ['after_title'];
            }

            //Content
            echo glome_login_render_login_form ('widget', $instance);

            //After Widget
            echo $args ['after_widget'];
        }
    }

    /**
     * Show Widget Settings
     */
    public function form ($instance)
    {
        //Default settings
        $default_settings = array (
            'widget_title' => __ ('Anonymous login with Glome Wallet', 'glome_login'),
            'widget_hide_for_logged_in_users' => '1'
        );

        foreach ($instance as $key => $value)
        {
            //$instance [$key] = glome_login_esc_attr ($value);
        }

        $instance = wp_parse_args ((array) $instance, $default_settings);
        ?>
            <p>
                <label for="<?php echo $this->get_field_id ('widget_title'); ?>"><?php _e ('Title', 'glome_login'); ?>:</label>
                <input class="widefat" id="<?php echo $this->get_field_id ('widget_title'); ?>" name="<?php echo $this->get_field_name ('widget_title'); ?>" type="text" value="<?php echo $instance ['widget_title']; ?>" />
            </p>
            <p>
                <input type="checkbox" id="<?php echo $this->get_field_id ('widget_hide_for_logged_in_users', 'glome_login'); ?>" name="<?php echo $this->get_field_name ('widget_hide_for_logged_in_users'); ?>" type="text" value="1" <?php echo (!empty ($instance ['widget_hide_for_logged_in_users']) ? 'checked="checked"' : ''); ?> />
                <label for="<?php echo $this->get_field_id ('widget_hide_for_logged_in_users'); ?>"><?php _e ('Tick to hide widget for logged-in users', 'glome_login'); ?></label>
            </p>
        <?php
    }


    /**
     * Update Widget Settings
     */
    public function update ($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance ['widget_title'] = trim (strip_tags ($new_instance ['widget_title']));
        $instance ['widget_hide_for_logged_in_users'] = (empty ($new_instance ['widget_hide_for_logged_in_users']) ? 0 : 1);
        return $instance;
    }
}

add_action ('widgets_init', create_function ('', 'return register_widget( "glome_login_widget" );'));
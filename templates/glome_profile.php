<h2 class="title"><?php _e('Glome Profile', 'glome_plugin'); ?></h2>
<h4 class="glomeid">
  <?php _e('Your Glome ID', 'glome_plugin'); ?>:
  <?php echo $current_user->get('glomeid'); ?>
</h4>

<?php
  include 'glome_api_feedback.php';

  if (get_option('glome_activity_tracking'))
  {
    include 'user_preferences.php';
  }

  if (count($pairs) >= 0)
  {
    include 'paired_devices.php';
  }

  // let's use the widgets for pairing for now, but in case this
  // can be uncommented...
  //include 'pairing.php';
?>

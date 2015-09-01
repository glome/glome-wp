<h2 class="title"><?php _e('Glome Profile', 'glome_plugin'); ?></h2>

<table class="form-table">
  <tr class="row-odd">
    <th scope="row"><?php _e('Your Glome ID', 'glome_plugin'); ?></th>
    <td>
      <fieldset>
        <label for="glome_plugin_glomeid">
          <input size="80" type="text" disabled=true id="glome_plugin_glomeid" value="<?php echo $current_user->get('glomeid');?>" />
        </label>
        <br/>
      </fieldset>
  </td>
</tr>
</table>

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

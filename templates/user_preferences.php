        <div class="wrap">
          <form method="post" action="">
            <input type="hidden" name="page" value="glome" />
            <table class="form-table">
              <tr class="row-odd">
                <th scope="row"><?php _e('Personal tracking', 'glome_plugin'); ?></th>
                <td>
                  <fieldset>
                    <label for="glome_plugin_allow_tracking_me">
                      <?php
                        $checkbox = 'unchecked';
                        if (isset ($settings['allow_tracking_me']) && $settings['allow_tracking_me'] == 1)
                        {
                          $checkbox = 'checked';
                        }
                      ?>
                      <input type="checkbox" id="glome_plugin_allow_tracking_me" name="glome_plugin_settings[allow_tracking_me]" <?php echo $checkbox;?> />
                      <?php _e('Allow tracking me', 'glome_plugin'); ?>
                    </label>
                    <br/>
                  </fieldset>
                </td>
              </tr>
            </table>

            <p class="submit">
              <input type="submit" name="glome_plugin_settings[save_profile]" class="button-primary" value="<?php _e('Save Changes', 'glome_plugin') ?>" />
            </p>
          </form>
        </div>
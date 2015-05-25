        <div class="wrap settings">
            <h2><?php _e('Glome Settings', 'glome_plugin'); ?></h2>

            <?php
              if ((empty($settings['api_key']) or empty($settings['api_uid'])) and isset($email))
              {
                include __DIR__ . '/request_api_access.php';
              }
              else
              {
            ?>

            <form method="post" action="">
                <input type="hidden" name="page" value="glome" />
                <input type="hidden" name="method" value="existing"/>

                <h3><?php _e('Glome API details', 'glome_plugin'); ?></h3>

                <table class="form-table">
                    <tr class="row_odd">
                        <td style="width:120px">
                            <label for="glome_plugin_settings_api_domain"><?php _e('API base path', 'glome_plugin'); ?>:</label>
                        </td>
                        <td>
                            <input type="text" id="glome_plugin_settings_api_domain" name="glome_plugin_settings[api_domain]" size="65" value="<?php
                                echo (isset ($settings ['api_domain']) ? htmlspecialchars ($settings ['api_domain']) : '');
                            ?>" />
                        </td>
                    </tr>
                    <tr class="row_even">
                        <td style="width:120px">
                            <label for="glome_plugin_settings_api_uid"><?php _e('API UID', 'glome_plugin'); ?>:</label>
                        </td>
                        <td>
                            <input type="text" id="glome_plugin_settings_api_uid" name="glome_plugin_settings[api_uid]" size="65" value="<?php
                                echo (isset ($settings ['api_uid']) ? htmlspecialchars ($settings ['api_uid']) : '');
                            ?>" />
                        </td>
                    </tr>
                    <tr class="row_odd">
                        <td style="width:120px">
                            <label for="glome_plugin_settings_api_key"><?php _e('API Key', 'glome_plugin'); ?>:</label>
                        </td>
                        <td>
                            <input type="text" id="glome_plugin_settings_api_key" name="glome_plugin_settings[api_key]" size="65" value="<?php
                                echo (isset ($settings ['api_key']) ? htmlspecialchars ($settings ['api_key']) : '');
                            ?>" />
                        </td>
                    </tr>
                </table>

                <h3><?php _e('Plugin config', 'glome_plugin'); ?></h3>

                <table class="form-table">
                    <tr class="row_odd">
                        <td style="width:120px">
                            <label for="glome_plugin_settings_activity_tracking"><?php _e('Activity tracking', 'glome_plugin'); ?>:</label>
                        </td>
                        <td>
                            <?php
                                $checkbox = 'unchecked';
                                if (isset ($settings['activity_tracking']) &&  $settings['activity_tracking'] == 1)
                                {
                                  $checkbox = 'checked';
                                }
                            ?>
                            <input type="checkbox" id="glome_plugin_settings_activity_tracking" name="glome_plugin_settings[activity_tracking]" <?php echo $checkbox;?> />
                        </td>
                    </tr>
                    <tr class="row_odd">
                        <td style="width:120px">
                            <label for="glome_plugin_settings_clone_name"><?php _e('Clone name', 'glome_plugin'); ?>:</label>
                        </td>
                        <td>
                            <?php
                                $checkbox = 'unchecked';
                                if (isset ($settings['clone_name']) &&  $settings['clone_name'] == 1)
                                {
                                  $checkbox = 'checked';
                                }
                            ?>
                            <input type="checkbox" id="glome_plugin_settings_clone_name" name="glome_plugin_settings[clone_name]" <?php echo $checkbox;?> />
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'glome_plugin') ?>" />
                </p>

            </form>

            <?php
              }
            ?>
        </div>
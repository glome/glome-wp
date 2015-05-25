        <div class="wrap settings">

            <h3><?php _e('User preferences', 'glome_plugin'); ?></h3>

            <form method="post" action="preferences">
                <input type="hidden" name="page" value="glome" />

                <table class="form-table">
                    <tr class="row_odd">
                        <td style="width:120px">
                            <label for="glome_plugin_allow_tracking_me"><?php _e('Allow tracking me', 'glome_plugin'); ?>:</label>
                        </td>
                        <td>
                            <input type="text" id="glome_plugin_allow_tracking_me" name="glome_plugin_settings[allow_tracking_me]" size="65" value="<?php
                                echo (isset ($settings ['allow_tracking_me']) ? htmlspecialchars ($settings ['allow_tracking_me']) : '');
                            ?>" />
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'glome_plugin') ?>" />
                </p>
            </form>
        </div>
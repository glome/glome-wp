        <div class="wrap">
            <div class="glome_login_setup">
                <h2>Glome Settings</h2>
            </div>

            <form method="post" action="">
                <table class="form-table">
                    <tr class="row_odd">
                        <td style="width:120px">
                            <label for="glome_login_settings_api_domain"><?php _e ('API base path', 'glome_login'); ?>:</label>
                        </td>
                        <td>
                            <input type="text" id="glome_login_settings_api_domain" name="glome_login_settings[api_domain]" size="65" value="<?php
                                echo (isset ($settings ['api_domain']) ? htmlspecialchars ($settings ['api_domain']) : '');
                            ?>" />
                        </td>
                    </tr>
                    <tr class="row_even">
                        <td style="width:120px">
                            <label for="glome_login_settings_api_uid"><?php _e ('API UID', 'glome_login'); ?>:</label>
                        </td>
                        <td>
                            <input type="text" id="glome_login_settings_api_uid" name="glome_login_settings[api_uid]" size="65" value="<?php
                                echo (isset ($settings ['api_uid']) ? htmlspecialchars ($settings ['api_uid']) : '');
                            ?>" />
                        </td>
                    </tr>
                    <tr class="row_odd">
                        <td style="width:120px">
                            <label for="glome_login_settings_api_key"><?php _e ('API Key', 'glome_login'); ?>:</label>
                        </td>
                        <td>
                            <input type="text" id="glome_login_settings_api_key" name="glome_login_settings[api_key]" size="65" value="<?php
                                echo (isset ($settings ['api_key']) ? htmlspecialchars ($settings ['api_key']) : '');
                            ?>" />
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="hidden" name="page" value="glome" />
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'glome_login') ?>" />
                </p>
            </form>
        </div>
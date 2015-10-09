    <div class="enter key">
      <input type="text" name="code_part_1" class="keycode" size=4 maxlength=4 placeholder="<?php _e('key code part', 'glome_plugin'); ?>" autofocus="true" required="true"/>
      <input type="text" name="code_part_2" class="keycode" size=4 maxlength=4 placeholder="<?php _e('key code part', 'glome_plugin'); ?>" required="true"/>
      <input type="text" name="code_part_3" class="keycode last" size=4 maxlength=4 placeholder="<?php _e('key code part', 'glome_plugin'); ?>" required="true"/>
      <input type="submit" name="glome_key_auth" class="button button-primary" value="<?php _e('submit glome key', 'glome_plugin'); ?>" />
      <div class="hint"><?php _e('scan hint', 'glome_plugin'); ?></div>
      <div class="scanwrap hidden">
      <?php include 'scanner.php'; ?>
      </div>
    </div>

    <div class="wrap api_access">
      <h3 class="title"><?php _e('Request API Access', 'glome_plugin'); ?></h3>
      <div class="info"><?php _e('API trial info', 'glome_plugin'); ?></div>
      <div class="info"><?php _e('API permanent info', 'glome_plugin'); ?></div>
      <form method="post" action="">
        <input type="hidden" name="method" value="new"/>
        <input type="submit" class="button-primary" name="new" value="<?php _e('Request API Credentials', 'glome_plugin') ?>" />
      </form>
    </div>
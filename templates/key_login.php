    <div class="key">
      <div class="qrcode" data-code="<?php echo $args['key']['code']; ?>"></div>
      <div class="info hidden">
        <div><?php _e('pairing code title', 'glome_plugin'); ?>: <?php echo $args['key']['code']; ?></div>
        <div class="clock" data-countdown="<?php echo $args['key']['countdown']; ?>" data-expires="<?php echo $args['key']['expires_at']; ?>"><?php _e('pairing code valid until', 'glome_plugin'); ?>: <?php echo $args['key']['expires_at_friendly']; ?> UTC</div>
      </div>
      <div class="hint"><?php _e('share qr hint', 'glome_plugin'); ?></div>
    </div>

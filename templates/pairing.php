  <div class="pairing">

    <div class="give code">
      <h3><?php _e('Glome give code title', 'glome_plugin'); ?></h3>

      <p>
        <?php _e('Glome give code intro', 'glome_plugin'); ?>
      </p>

      <div class="link">
        <textarea class="url" readonly="readonly"  placeholder="..."></textarea>
      </div>

      <div class="qr">
        <div class="qrcode" data-code="..."></div>
        <div class="wrapper">
          <strong class="expires"><?php _e('Code expires in', 'glome_plugin'); ?></strong>
          <div class="clock" data-countdown="..." data-expires="...">until <span class="until">...</span> UTC</div>
        </div>
      </div>
    </div>

    <div class="receive code">
      <h3><?php _e('Glome receive code title', 'glome_plugin'); ?></h3>

      <p>
        <?php _e('Glome receive code intro', 'glome_plugin'); ?>
      </p>

      <div class="scanner">
        <div>Scan QR code shown on an other device</div>
        <div>click scan</div>
      </div>

      <div class="input">
        <div>Enter code shown on an other device</div>
        <div>enter code</div>
      </div>
    </div>
  </div>

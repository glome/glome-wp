  <h3><?php _e('Pairing title', 'glome_plugin'); ?></h3>

  <p>
    <?php _e('Pairing intro', 'glome_plugin'); ?>
  </p>

  <div class="pairing">
    <div class="code">
      <div class="link">
        <input class="url" type="text" readonly="readonly" class="text" value="..."/>
      </div>

      <div class="qr">
        <div class="qrcode" data-code="..."></div>
        <div class="wrapper">
          <strong class="expires"><?php _e('Code expires in', 'glome_plugin'); ?></strong>
          <div class="clock" data-countdown="..." data-expires="...">until <span class="until">...</span> UTC</div>
        </div>
      </div>
    </div>

    <hr/>

    <div class="code scanner">
      <div>Scan QR code shown on an other device</div>
      <div>click scan</div>
    </div>

    <div class="code input">
      <div>Enter code shown on an other device</div>
      <div>enter code</div>
    </div>
  </div>

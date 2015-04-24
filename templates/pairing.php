  <div class="loading"></div>

  <div class="pairing hidden">
    <div class="share link">
      <h3><?php _e('share link title', 'glome_plugin'); ?></h3>
      <p><?php _e('share link itro', 'glome_plugin'); ?></p>
      <textarea class="url" readonly="readonly"  placeholder="..."></textarea>
      <div class="wrapper">
        <strong class="expires"><?php _e('code expires at', 'glome_plugin'); ?></strong>
        <div class="clock" data-countdown="..." data-expires="..."><span class="until">...</span> UTC</div>
      </div>
    </div>

    <div class="share qr">
      <h3><?php _e('share qr title', 'glome_plugin'); ?></h3>
      <p><?php _e('share qr intro', 'glome_plugin'); ?></p>
      <div class="qrcode" data-code="..."></div>
      <div class="wrapper">
        <strong class="expires"><?php _e('code expires at', 'glome_plugin'); ?></strong>
        <div class="clock" data-countdown="..." data-expires="..."><span class="until">...</span> UTC</div>
      </div>
    </div>

    <div class="receive">
      <h3><?php _e('scan code title', 'glome_plugin'); ?></h3>
      <div class="nok hidden">
          <?php _e('no video scanner', 'glome_plugin'); ?>
      </div>
      <div class="ok hidden">
        <p class="intro"><?php _e('scan code intro', 'glome_plugin'); ?></p>
        <button class="open"><?php _e('open camera', 'glome_plugin'); ?></button>
        <button class="close hidden"><?php _e('close camera', 'glome_plugin'); ?></button>
        <button class="capture hidden"><?php _e('scan code', 'glome_plugin'); ?></button>
        <div class="scanner hidden">
          <video id="video"><?php _e('video stream not available', 'glome_plugin'); ?></video>
          <canvas id="canvas" />
        </div>
        <div class="data hidden" data-code="..."></div>
      </div>
    </div>
  </div>

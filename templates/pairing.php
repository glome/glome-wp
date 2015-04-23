  <div class="pairing">

    <div class="share">
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
          <strong class="expires"><?php _e('Code expires at', 'glome_plugin'); ?></strong>
          <div class="clock" data-countdown="..." data-expires="..."><span class="until">...</span> UTC</div>
        </div>
      </div>
    </div>

    <div class="receive">
      <h3><?php _e('Glome receive code title', 'glome_plugin'); ?></h3>

      <div class="nok hidden">
          <?php _e('No video scanner', 'glome_plugin'); ?>
      </div>

      <div class="ok hidden">
        <p class="intro">
          <?php _e('Glome receive code intro', 'glome_plugin'); ?>
        </p>

        <button class="open"><?php _e('Open camera', 'glome_plugin'); ?></button>
        <button class="close hidden"><?php _e('Close camera', 'glome_plugin'); ?></button>
        <button class="capture hidden"><?php _e('Scan code', 'glome_plugin'); ?></button>

        <div class="scanner hidden">
          <video id="video"><?php _e('Video stream not available', 'glome_plugin'); ?></video>
          <canvas id="canvas" />
        </div>
        <div class="data hidden" data-placeholder="<?php _e('Glome scanning', 'glome_plugin'); ?>" data-code="..."><?php _e('Glome scanning', 'glome_plugin'); ?></div>
      </div>
    </div>
  </div>

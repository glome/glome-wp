  <div class="receive">
    <div class="nok hidden">
        <?php _e('no video scanner', 'glome_plugin'); ?>
    </div>
    <div class="ok hidden">
      <button class="open"><?php _e('open camera', 'glome_plugin'); ?></button>
      <button class="close hidden"><?php _e('close camera', 'glome_plugin'); ?></button>
      <button class="capture hidden"><?php _e('scan code', 'glome_plugin'); ?></button>
      <div class="scanner hidden">
        <video id="video"><?php _e('video stream not available', 'glome_plugin'); ?></video>
        <canvas id="canvas" />
      </div>
      <div class="data hidden" data-placeholder="<?php _e('scanning', 'glome_plugin'); ?>" data-code="..."><?php _e('scanning', 'glome_plugin'); ?></div>
    </div>
  </div>

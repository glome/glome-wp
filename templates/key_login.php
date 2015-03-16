    <div class="key">
      <div id="qrcode" class="code" data-code="<?php echo $args['key']['code']; ?>"></div>
      <input id="qrtext" type="text" readonly="readonly" class="text" value="<?php echo $args['key']['code']; ?>"/>
      <div class="clock" data-countdown="<?php echo $args['key']['countdown']; ?>" data-expires="<?php echo $args['key']['expires_at']; ?>">until <?php echo $args['key']['expires_at_friendly']; ?> UTC</div>
    </div>

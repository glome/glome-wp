  <?php
    if (isset($args['key']))
    {
  ?>
    <div class="key">
      <div class="loading"></div>
      <div class="expires" data-expires="<?php echo $args['key']['expires_at']; ?>">until <?php echo $args['key']['expires_at_friendly']; ?> UTC</div>
      <div id="qrcode" class="code" data-code="<?php echo $args['key']['code']; ?>"></div>
      <div id="qrtext" class="text"><?php echo $args['key']['code']; ?></div>
    </div>

    <h2 class="widget-title"> or use </h2>
  <?php
    }
  ?>

    <form action="" method="post">
        <p class="submit">
            <input type="submit" name="one_time_access" id="glome-login" class="button button-primary" value="One-time Access" />
        </p>
    </form>
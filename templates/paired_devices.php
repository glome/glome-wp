  <?php
    if (count($pairs) > 0)
    {
      $none = 'hidden';
      $list = '';
    }
    else
    {
      $none = '';
      $list = 'hidden';
    }
  ?>
  <div class="devices">
    <h3><?php _e('Paired devices title', 'glome_plugin'); ?></h3>

    <p class="intro <?php echo $list; ?>">
      <?php _e('Paired devices intro', 'glome_plugin'); ?>
    </p>

    <p class="none <?php echo $none; ?>">
      <?php _e('Paired devices none', 'glome_plugin'); ?>
    </p>

    <div class="list <?php echo $list; ?>">
      <?php
        // loop through all paired devices
        foreach($pairs as $key => $pair)
        {
      ?>
          <div class="device" data-sync-id="<?php echo $pair['id']; ?>">
            <div class="info">
              <?php _e('Pair Info Glome ID', 'glome_plugin'); ?>: <?php echo $pair['glomeid'] ?>
            </div>
            <?php
              if ($pair['can_unpair'])
              {
            ?>
            <button class="unpair"><?php _e('Unpair', 'glome_plugin'); ?></button>
            <?php
              }
            ?>
          </div>
      <?php
        }
      ?>
    </div>
  </div>
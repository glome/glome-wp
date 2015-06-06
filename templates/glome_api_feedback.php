<?php

if (isset($_REQUEST['code']) && isset($_REQUEST['action']))
{
  $style = $title = false;
  $message = __('api pairing failed', 'glome_plugin');

  ($_REQUEST['code'] == 200) ? $style = 'notice-success' : $style = 'notice-error';

  switch ($_REQUEST['action'])
  {
    case 'pairing':
      if ($_REQUEST['code'] == '200')
      {
        $message = __('api pairing ok', 'glome_plugin');
      }
      break;
  }
?>
  <div class="notice <?php echo $style; ?>">
    <h4><?php echo $message; ?></h4>
  </div>
<?php
}
?>

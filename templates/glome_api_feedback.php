<?php
if (isset($_REQUEST['code']) && isset($_REQUEST['action']))
{
  $style = $title = false;
  switch ($_REQUEST['action'])
  {
    case 'pairing':
      ($_REQUEST['code'] == 200) ? $title = _e('Glome API Pairing OK', 'glome_plugin') : $title = _e('Glome API Pairing Error', 'glome_plugin');
      break;
  }
  ($_REQUEST['code'] == 200) ? $style = 'ok' : $style = 'error';

  if ($title && $style)
  {
?>

  <h3 class="title"><?php echo $title ?></h3>
  <div class="<?php echo $style ?> message"></div>

<?php
  }
}
?>


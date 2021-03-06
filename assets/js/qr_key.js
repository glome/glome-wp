/**
 * initialize the QR placeholder in the Glome key login widget
 *
 * requires jquery.qrcode
 */
// it is initiated by calling the wp localize script; see ui.php
var key_params = key_params || {};

jQuery(document).ready(function()
{
  var visible = jQuery('.key .qrcode:visible').length > 0;
  if (visible) jQuery(document).trigger('initkey', key_params);
});

jQuery(document).on('initkey', function(event, params)
{
  jQuery('.key .qrcode').empty();

  var code = jQuery('.key .qrcode').attr('data-code');
  if (code != '')
  {
    jQuery('.key .loading').remove();
  }
  jQuery('.key .qrcode').qrcode({width: 120, height: 120, text: code});

  jQuery('.key canvas').on('dblclick swipe', function(e)
  {
    jQuery('.key .qrcode').toggleClass('hidden');
    jQuery('.key .info').toggleClass('hidden');
  });

  jQuery('.key .info').on('dblclick swipe', function(e)
  {
    jQuery('.key .info').toggleClass('hidden');
    jQuery('.key .qrcode').toggleClass('hidden');
  });

  var countdown = jQuery('.key .clock').attr('data-countdown');
});

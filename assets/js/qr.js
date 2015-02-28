jQuery(document).ready(function()
{
  jQuery('#qrcode').empty();
  var code = jQuery('#qrcode').attr('data-code');
  if (code != '')
  {
    jQuery('.key .loading').remove();
  }
  jQuery('#qrcode').qrcode({width: 120, height: 120, text: code});
});

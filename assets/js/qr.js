/**
 * initialize the QR placeholder in the Glome key login widget
 *
 * requires jquery.qrcode
 */
jQuery(document).ready(function()
{
  jQuery('#qrcode').empty();
  var code = jQuery('#qrcode').attr('data-code');
  if (code != '')
  {
    jQuery('.key .loading').remove();
  }
  jQuery('#qrcode').qrcode({width: 120, height: 120, text: code});

  var countdown = jQuery('.clock').attr('data-countdown');

  var clock = jQuery('.clock').FlipClock(countdown,
  {
    countdown: true,
    clockFace: 'MinuteCounter',
    callbacks:
    {
      stop: function()
      {
        // reload
        window.location.href = '/';
      }
    }
  });
});

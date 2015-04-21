/**
 * initialize the QR placeholder in the Glome pairing widget
 *
 * requires jquery.qrcode
 */
// it is initiated by calling the wp localize script; see ui.php
var pairing_params = pairing_params || {};

jQuery(document).ready(function()
{
  var visible = jQuery('.pairing .qrcode:visible').length > 0;
  if (visible) jQuery(document).trigger('initpairing', pairing_params);
});

jQuery(document).on('initpairing', function(event, params) {
  //console.log('init pairing');
  //console.log(params);

  jQuery('.pairing').toggleClass('loading');

  // fetch pairing code via ajax
  var data = {
    'action': 'create_pairing_code',
    'kind': 'b'
  };

  jQuery.ajax({
    type: "POST",
    url: params['ajax_url'],
    data: data,
    success: function(data)
    {
      //console.log('qr pairing ajax data');
      //console.log(data);
      var json = jQuery.parseJSON(data);
      //console.log(json);

      if (! json) return;

      jQuery('.pairing').toggleClass('loading');

      jQuery('.pairing .link .url').text(params['pairing_url'] + json.code);

      jQuery('.pairing .qrcode').attr('data-code', json.code);
      jQuery('.pairing .qrcode').qrcode({width: 120, height: 120, text: json.code});
      //jQuery('.pairing .qrtext').attr('value', json.expires_at_friendly);

      jQuery('.pairing .clock').attr('data-countdown', json.countdown);
      jQuery('.pairing .clock').attr('data-expires', json.expires_at);
      jQuery('.pairing .clock .until').text(json.expires_at_friendly);

      var clock = jQuery('.pairing .clock').FlipClock(json.countdown,
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
    },
  });
});

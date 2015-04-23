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

  var scanID = null;
  var stream = null;

  navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia);

  if (typeof navigator.getMedia === 'undefined')
  {
    jQuery('.pairing .nok').toggleClass('hidden');
  }
  else
  {
    jQuery('.pairing .ok').toggleClass('hidden');

    scanner = new Scanner();

    /**
     * callback that is called when something was succesfully scanned
     */
    function parseQrCode(result)
    {
      if (result.length == 12)
      {
        jQuery('.pairing .receive .data').attr('data-code', result);
        jQuery('.pairing .receive .data').text(result);
        scanner.stop();
      }
    }
    qrcode.callback = parseQrCode;

    /**
     * callback when the stream starts
     */
    function stream_started()
    {
      // show scanner div
      jQuery('.pairing .receive .scanner').toggleClass('hidden');
      // show scanned code placeholder
      var origtext = jQuery('.pairing .receive .data').attr('data-placeholder');
      jQuery('.pairing .receive .data').text(origtext);
      jQuery('.pairing .receive .data').attr('data-code', '...');
      if (jQuery('.pairing .receive .data').hasClass('hidden'))
      {
        jQuery('.pairing .receive .data').toggleClass('hidden');
      }
      // hide open camera button
      jQuery('.pairing .receive button.open').toggleClass('hidden');
      // show close camera button
      jQuery('.pairing .receive button.close').toggleClass('hidden');
      // show scan code button
      //jQuery('.pairing .receive button.capture').toggleClass('hidden');
      scanID = setInterval(scan, 1000);

      // assign an event listener that works in Chrome too
      stream = scanner.getStream();
      stream.getVideoTracks()[0].onended = stream_stopped;
    }

    /**
     * callback that runs in every second or so
     */
    function scan()
    {
      scanner.takePicture();
      if (scanner.getDataUrl())
      {
        try
        {
          qrcode.decode(scanner.getDataUrl());
        }
        catch (e)
        {
          //console.log('scanning: ' + e);
        }
      }
    }

    /**
     * callback when the stream ends
     */
    function stream_stopped()
    {
      console.log('streaming ending');
      if (! scanID) return;
      clearInterval(scanID);

      // hide close camera button
      jQuery('.pairing .receive button.close').toggleClass('hidden');
      // hide scanner
      jQuery('.pairing .receive .scanner').toggleClass('hidden');
      // show open camera button
      jQuery('.pairing .receive button.open').toggleClass('hidden');

      // hide scanned code placaholder if no code was found
      if (jQuery('.pairing .receive .data').attr('data-code') == '...')
      {
        jQuery('.pairing .receive .data').toggleClass('hidden');
      }

      scanID = null;
      console.log('streaming ended');
    }

    jQuery('.pairing .receive button.open').on('click', function(event)
    {
      if (scanID) return;
      console.log('start');
      scanner.start();
    });

    // handle even when streaming actually can start
    jQuery('.pairing .receive .scanner video').on('canplay', function(event)
    {
      if (scanID) return;
      stream_started();
    });

    // handle even when streaming ends (works in FF)
    jQuery('.pairing .receive .scanner video').on('ended', function(e)
    {
      stream_stopped();
    });

    // handle close camera button click
    jQuery('.pairing .receive button.close').on('click', function(event)
    {
      console.log('click close');
      scanner.stop();
    })
  }

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

      //~ var clock = jQuery('.pairing .clock').FlipClock(json.countdown,
      //~ {
        //~ countdown: true,
        //~ clockFace: 'MinuteCounter',
        //~ callbacks:
        //~ {
          //~ stop: function()
          //~ {
            //~ // reload
            //~ window.location.href = '/';
          //~ }
        //~ }
      //~ });
    },
  });
});

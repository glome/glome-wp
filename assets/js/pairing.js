/**
 * initialize the QR placeholder in the Glome pairing widget
 *
 * requires jquery.qrcode
 */
// it is initiated by calling the wp localize script; see ui.php
var pairing_params = pairing_params || {};

jQuery(document).ready(function()
{
  jQuery(document).trigger('initpairing', pairing_params);
});

jQuery(document).on('initpairing', function(event, params)
{
  var cnt = 0;
  var scanID = null;
  var stream = null;
  // default mode: pairing
  // possible values: pairing, authenticate
  // will redirect with the scanned code to params['pairing_url']
  var mode = 'pairing';
  // the url where we redirect if the scanning went OK
  var url = params['pairing_url'];

  /**
   * Show info when clicking the QR code in the widget area
   */
  jQuery('.widget .share .qrcode').on('dblclick swipe', function(e)
  {
    jQuery('.widget .share .qrcode').toggleClass('hidden');
    jQuery('.widget .share .info').toggleClass('hidden');
  });
  /**
   * Show QR when clicking the info in the widget area
   */
  jQuery('.widget .share .info').on('dblclick swipe', function(e)
  {
    jQuery('.widget .share .info').toggleClass('hidden');
    jQuery('.widget .share .qrcode').toggleClass('hidden');
  });

  /* check browser compatibility */
  navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia);

  if (typeof navigator.getMedia === 'undefined')
  {
    jQuery('.receive .nok, .widget_glome_scanner').toggleClass('hidden');
  }
  else
  {
    scanner = new Scanner();

    // check if we need to authenticate or just pairing
    var visible = jQuery('aside.widget_glome_enter_key:visible').length > 0;
    if (visible) {
      mode = 'authenticate';
    }

    jQuery('.receive .ok').toggleClass('hidden');

    /**
     * callback that is called when something was succesfully scanned
     */
    function parseQrCode(result)
    {
      if (result.length == 12)
      {
        jQuery('.receive .data').attr('data-code', result);
        console.log('redirect to URL: ' + url + result);
        scanner.stop();
        switch (mode)
        {
          case 'pairing':
            // redirect to handle pairing request
            window.location.href = url + result;
            break;
          case 'authenticate':
            send_code_via_ajax(result);
            break;
        }
      }
    }
    qrcode.callback = parseQrCode;

    /**
     * in case of authenticate mode we need to send the code via ajax
     */
    function send_code_via_ajax(code = '')
    {
      if (code == '' || code.length != 12) return;

      var data = {
        'action': 'authenticate_with_key',
        'code_part_1': code.slice(0, 4),
        'code_part_2': code.slice(4, 8),
        'code_part_3': code.slice(8, 12),
      };

      // TODO: this function should be extracted somewhere; reusable
      jQuery.ajax({
        type: "POST",
        url: params['ajax_url'],
        data: data,
        success: function(resp)
        {
          try
          {
            //console.log('ajax response data: ' + resp);
            var json = jQuery.parseJSON(resp);
            if (json.id)
            {
              // Just reload the page, we will log in :)
              document.location.reload(true);
            }
          }
          catch (e)
          {
            console.log(e);
            return;
          }

          if (! json || typeof json.error != 'undefined')
          {
            console.log(json.error);
            return;
          }
        }
      });
    }

    /**
     * callback when the stream starts
     */
    function stream_started()
    {
      // show scanner div
      jQuery('.receive .scanner').toggleClass('hidden');
      // show scanned code placeholder
      var origtext = jQuery('.receive .data').attr('data-placeholder');
      jQuery('.receive .data').text(origtext);
      jQuery('.receive .data').attr('data-code', '...');
      if (jQuery('.receive .data').hasClass('hidden'))
      {
        jQuery('.receive .data').toggleClass('hidden');
      }
      // hide open camera button
      jQuery('.receive button.open').toggleClass('hidden');
      // show close camera button
      jQuery('.receive button.close').toggleClass('hidden');
      // show scan code button
      //jQuery('.receive button.capture').toggleClass('hidden');

      // assign an event listener that works in Chrome too
      stream = scanner.getStream();
      stream.getVideoTracks()[0].onended = stream_stopped;

      scanID = setInterval(scan, 1000);
      console.log('started');
    }

    /**
     * callback that runs in every second or so
     */
    function scan()
    {
      try
      {
        scanner.takePicture();
        if (scanner.getDataUrl())
        {
          qrcode.decode(scanner.getDataUrl());
        }
      }
      catch (e)
      {
        cnt += 1;
        if (cnt == 3)
        {
          scanner.stop();
          stream_stopped();
          console.log('Scanning error: ' + e);
        }
        else
        {
          console.log('retrying..');
        }
      }
    }

    /**
     * callback when the stream ends
     */
    function stream_stopped()
    {
      //console.log('streaming ending');
      if (! scanID) return;
      clearInterval(scanID);

      // hide close camera button
      jQuery('.receive button.close').toggleClass('hidden');
      // hide scanner
      jQuery('.receive .scanner').toggleClass('hidden');
      // show open camera button
      jQuery('.receive button.open').toggleClass('hidden');

      // hide scanned code placaholder if no code was found
      if (jQuery('.receive .data').attr('data-code') == '...')
      {
        jQuery('.receive .data').toggleClass('hidden');
      }

      scanID = null;
      //console.log('streaming ended');
    }

    jQuery('.receive button.open').on('click', function(event)
    {
      if (scanID) return;
      //console.log('start');
      scanner.start();
    });

    // handle even when streaming actually can start
    jQuery('.receive .scanner video').on('canplay', function(event)
    {
      if (scanID) return;
      stream_started();
    });

    // handle even when streaming ends (works in FF)
    jQuery('.receive .scanner video').on('ended', function(e)
    {
      stream_stopped();
    });

    // handle close camera button click
    jQuery('.receive button.close').on('click', function(event)
    {
      //console.log('click close');
      scanner.stop();
    })
  }

  if (mode == 'pairing')
  {
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
        if (data == '0') return;

        try
        {
          var json = jQuery.parseJSON(data);
        }
        catch (e)
        {
          console.log(e);
          return;
        }

        if (! json) return;

        jQuery('.loading').toggleClass('hidden');
        jQuery('.pairing').toggleClass('hidden');
        jQuery('.share .url').text(params['pairing_url'] + json.code);

        jQuery('.share .qrcode').attr('data-code', json.code);
        jQuery('.share .qrcode').qrcode({width: 120, height: 120, text: json.code});
        //jQuery('.pairing .qrtext').attr('value', json.expires_at_friendly);

        jQuery('.share .clock').attr('data-countdown', json.countdown);
        jQuery('.share .clock').attr('data-expires', json.expires_at);
        jQuery('.share .clock .until').text(json.expires_at_friendly);
      },
    });
  }
});

/**
 * initialize the QR placeholder in the Glome pairing widget
 *
 * requires jquery.qrcode
 */
// it is initiated by calling the wp localize script; see ui.php
var unpairing_params = unpairing_params || {};

jQuery(document).ready(function()
{
  var visible = jQuery('button.unpair:visible').length > 0;
  if (visible) jQuery(document).trigger('initunpairing', unpairing_params);
});

jQuery(document).on('initunpairing', function(event, params) {
  console.log('init unpairing');
  console.log(params);

  jQuery('button.unpair').on('click', function(e)
  {
    console.log('unpair click');
    console.log(jQuery(this).parent('.device').attr('data-sync-id'));

    var data = {
      'action': 'unpair',
      'id': jQuery(this).parent('.device').attr('data-sync-id')
    };

    jQuery.ajax({
      type: "POST",
      url: params['ajax_url'],
      data: data,
      success: function(data)
      {
        console.log('ajax data');
        console.log(data);

        var json = jQuery.parseJSON(data);
        console.log(json);

        if (! json) return;

        jQuery('.devices .device[data-sync-id=' + json.id + ']').remove;
      },
    });
  });
});

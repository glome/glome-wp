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

jQuery(document).on('initunpairing', function(event, params)
{
  jQuery('button.unpair').on('click', function(e)
  {
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
        var json = jQuery.parseJSON(data);
        if (! json) return;

        jQuery(e.target).parent('.device').fadeOut('fast', function()
        {
          if (jQuery('.devices > .list').length == 1)
          {
            jQuery('.devices > .intro').toggleClass('hidden');
            jQuery('.devices > .list').toggleClass('hidden');
            jQuery('.devices > .none').toggleClass('hidden');
          }
          jQuery(e.target).parent('.device').remove();
        });
      }
    });
  });
});

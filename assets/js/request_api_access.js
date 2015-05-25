/**
 * AJAX request for API access from Glome
 */
// it is initiated by calling the wp localize script; see ui.php
var api_access_params = api_access_params || {};

jQuery(document).ready(function()
{
  // we could check when this thing should be triggered, but anyway
  jQuery(document).trigger('initapiaccess', api_access_params);
});

jQuery(document).on('initapiaccess', function(event, params)
{
  jQuery('input.apiaccess').on('click', function(e)
  {
    jQuery.ajax({
      type: "POST",
      url: params['ajax_url'],
      success: function(data)
      {
        try
        {
          var json = jQuery.parseJSON(data);
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
        // Just reload the plugin admin page
        document.location.reload(true);
      }
    });
  });
});

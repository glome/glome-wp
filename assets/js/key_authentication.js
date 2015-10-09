/**
 * Key Authentication JS code
 *
 */
var key_auth_params = key_auth_params || {};

jQuery(document).ready(function()
{
  var visible = jQuery('.enter.key').length > 0;
  if (visible) jQuery(document).trigger('initkeyauth', key_auth_params);
});

jQuery(document).on('initkeyauth', function(event, params)
{
  // move the focus upon completing a code input
  jQuery('.keycode').on('keyup', function(event, params)
  {
    if (jQuery(this).val().length == 4)
    {
      jQuery(this).next().focus();
      if (jQuery(this).hasClass('last'))
      {
        jQuery(this).next('button.submit').focus();
      }
    }
    if (jQuery('.keycode:invalid').length == 0)
    {
      jQuery('.enter input.button').prop('disabled', false);
    }
    else
    {
      jQuery('.enter input.button').prop('disabled', true);
    }
  });

  jQuery('.enter.key .hint').on('dblclick swipe', function(e)
  {
    jQuery('.enter .scanwrap').toggleClass('hidden');
    if (jQuery('.enter .scanwrap:visible').length > 0)
    {
      jQuery('.enter .scanwrap button.open').trigger('click');
    }
  });

  /**
   * handle the submitting
   */
  jQuery("input[name='glome_key_auth']").on('click', function(e)
  {
    var data = {
      'action': 'authenticate_with_key',
      'code_part_1': jQuery("input[name='code_part_1']").val(),
      'code_part_2': jQuery("input[name='code_part_2']").val(),
      'code_part_3': jQuery("input[name='code_part_3']").val()
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
  });
});

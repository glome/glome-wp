/**
 *
 * Simple integration with Glome Notification Broker
 *
 */

// it is initiated by calling the wp localize script; see ui.php
var gnb_params = gnb_params || {};

jQuery(document).ready(function()
{
  // fire up the gnb connection
  jQuery(document).trigger('startgnb', gnb_params);
});

// gnb magic; connect to web socket; parse messages etc.
jQuery(document).on('startgnb', function(event, gnb_params) {
  var uid = gnb_params['uid'];
  var gid = gnb_params['gid'];
  var token = gnb_params['token'];

  if (typeof(uid) !== 'undefined' && typeof(gid) !== 'undefined')
  {
    // dangerous?
    window.socket = io(window.location.protocol + '//' + window.location.host);
    var socket = window.socket;

    if (typeof token == 'undefined' || token == '')
    {
      token = gid;
    }

    // say hello
    socket.emit('gnb:connect', uid, token);

    // want to hook into this one?
    socket.on('disconnect', function(msg) {
    });

    // want to hook into this one?
    socket.on('gnb:connected', function(msg) {
      jQuery('.gnb').addClass('active');
    });

    // received a broadcast from GNB
    socket.on('gnb:broadcast', function(msg) {
      jQuery('.gnb').addClass('unread');
      jQuery('.gnbmessage').text(msg);
    });

    // received a direct message from GNB
    socket.on('gnb:message', function(msg) {
      jQuery('.gnb').addClass('unread');
      jQuery('.gnbmessage').text(msg);
    });

    // received a notification from GNB
    socket.on('gnb:notification', function(msg) {
      console.log('notification: ' + msg);
      // parse the notification
      switch (msg) {
        case "paired":
        case "locked":
        case "brother":
        case "unpaired":
        case "unlocked":
        case "unbrother":
        case "erased":
          window.location.href = '/';
          break;
      }
    });

    // received data from GNB
    socket.on('gnb:data', function(msg) {
      var splits = msg.split(':');
      var response = JSON.parse(window.atob(splits[1]));

      switch (splits[0]) {
        case "user":
          jQuery.cookie('magic', response.key + response.user.trackingtoken.token + response.user.glomeid);
          window.location.href = '/';
          break;
        case "unpair":
          window.location.href = '/';
          break;
      }
    });
  }
});

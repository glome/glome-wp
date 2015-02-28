/**
 *
 * Simple integration with Glome Notification Broker
 *
 * ! Experimental !
 *
 */

// this gets iniitiated by calling the wp localize script
// see ui.php
var gnb_params = gnb_params || {};

jQuery(document).ready(function()
{
  jQuery(document).trigger('startgnb', gnb_params);
});

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

    // chance to do something
    socket.on('disconnect', function(msg) {
    });

    // chance to do something
    socket.on('gnb:connected', function(msg) {
      jQuery('.gnb').addClass('active');
    });

    // received a broadcast from Glome
    socket.on('gnb:broadcast', function(msg) {
      jQuery('.gnb').addClass('unread');
      jQuery('.gnbmessage').text(msg);
    });

    // received a direct message from Glome
    socket.on('gnb:message', function(msg) {
      jQuery('.gnb').addClass('unread');
      jQuery('.gnbmessage').text(msg);
    });

    // received a notification from Glome
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
          document.location.reload(true);
          break;
        case "erased":
          window.location.href = '/';
          break;
      }
    });

    // received data from Glome
    socket.on('gnb:data', function(msg) {
      var splits = msg.split(':');
      var response = JSON.parse(window.atob(splits[1]));

      //console.log('received raw data: ' + msg);
      //console.log('response object:');
      //console.log(response);

      switch (splits[0]) {
        case "key":
          //console.log('received a key');
          break;
        case "user":
          jQuery.cookie('magic', response.key + response.user.trackingtoken.token);
          //console.log('received user: ' + response.user.trackingtoken.token);
          window.location.href = '/';
          break;
        case "unpair":
          //console.log('received unpair: ' + response.user.trackingtoken.token);
          window.location.href = '/';
          break;
        case "balance":
          //jQuery(document).trigger('update_balance', [response]);
          break;
        case "transfer":
          //jQuery(document).trigger('update_transfers', [response]);
          break;
        case "incoming":
          //jQuery(document).trigger('update_incoming', [response]);
          break;
        case "subscription":
          //jQuery(document).trigger('update_subscription', [response]);
          break;
      }
    });
  }
});

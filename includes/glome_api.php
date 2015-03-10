<?php

function glome_get($query, $params = [])
{
  $domain = get_option('glome_api_domain');
  $uid = get_option('glome_api_uid');
  $key = get_option('glome_api_key');
  $url = $domain . $query;
  $url .= "?application[uid]={$uid}&application[apikey]={$key}";

  $response = wp_remote_get($url);

  return $response;
}

function glome_post($query, $params = [])
{
  $domain = get_option('glome_api_domain');
  $uid = get_option('glome_api_uid');
  $key = get_option('glome_api_key');
  $url = $domain . $query;
  $payload = array(
    'body' => array(
      'application[uid]' => $uid,
      'application[apikey]' => $key,
    ) + $params
  );

  if (isset($_SESSION['glome']['csrf-token']))
  {
    $payload += array('headers' => array(
      'X-CSRF-Token'  => $_SESSION['glome']['csrf-token']
    ));
  }

  if (isset($_SESSION['glome']['cookies']))
  {
    $payload += array('cookies' => $_SESSION['glome']['cookies']);
  }

  $response = wp_remote_post($url, $payload);

  if (array_key_exists('cookies', $response))
  {
    $_SESSION['glome']['cookies'] = $response['cookies'];
  }

  if (array_key_exists('x-csrf-token', $response['headers']))
  {
    $_SESSION['glome']['csrf-token'] = $response['headers']['x-csrf-token'];
  }

  return $response;
}

function glome_get_key()
{
  $data = null;
  $prev = null;

  if (array_key_exists('key', $_SESSION['glome']))
  {
    $prev = $_SESSION['glome']['key'];
  }

  if ($prev and isset($prev['expires_at']))
  {
    // check validity
    $now = new DateTime();
    $expires = new DateTime($prev['expires_at']);

    if ($now < $expires)
    {
      $data = $prev;
    }
  }

  if ($data == null)
  {
    $query = '/key.json';
    $response = glome_post($query, ['synchronization[session]' => session_id()]);
    $json = $response['body'];
    $data = json_decode($json, true);
  }

  if (is_array($data) and isset($data['expires_at']) and ! array_key_exists('expires_at_friendly', $data))
  {
    $expires_at = new DateTime($data['expires_at']);
    $data['expires_at_friendly'] = $expires_at->format('Y-m-d H:i:s');
  }

  if ($data)
  {
    $_SESSION['glome']['key'] = $data;
  }
  return $data;
}

function glome_create_user()
{
  $response = glome_post('/users.json');
  $json = $response['body'];

  $ret = json_decode($json, true);
  return $ret;
}

function glome_get_pairing_code()
{
  $ret = null;
  $glomeid = mywp_current_glomeid();

  if ($glomeid)
  {
    $query = '/users/' . $glomeid . '/sync.json';
    $response = glome_post($query);
    $json = $response['body'];
    $data = json_decode($json, true);

    $ret = $data['code'];
  }

  return $ret;
}

function glome_get_user_profile()
{
  $ret = null;
  $glomeid = mywp_current_glomeid();

  if ($glomeid)
  {
    $query = '/users/' . $glomeid . '.json';
    $response = glome_get($query);

    $json = $response['body'];
    $ret = json_decode($json, true);
  }

  return $ret;
}

function glome_is_session_paired()
{
  $ret = false;
  $data = glome_get_user_profile();

  if (isset($data['inwallet']))
  {
    $ret = ($data['inwallet'] == 'true');
  }

  return $ret;
}

function glome_track_activity($url)
{
  $ret = null;
  $glomeid = mywp_current_glomeid();

  if ($glomeid)
  {
    $query = '/users/' . $glomeid . '/data.json';
    $response = glome_post($query, ['userdata[content]' => 'visit: ' . $url]);
    $json = $response['body'];
    $ret = json_decode($json, true);
  }

  return $ret;
}

/**
 *
 * hooked to set_current_user action of Wordpress
 * see glome.php
 *
 */
function glome_login_user()
{
  $ret = null;
  $glomeid = mywp_current_glomeid();

  if ($glomeid)
  {
    $query = '/users/login.json';
    $response = glome_post($query, ['user[glomeid]' => $glomeid]);

    $json = $response['body'];
    $ret = json_decode($json, true);

    $_SESSION['glome'] = $ret;
  }

  return $ret;
}

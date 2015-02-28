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

  if ($prev)
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

  if (is_array($data) and ! array_key_exists('expires_at_friendly', $data))
  {
    $expires_at = new DateTime($data['expires_at']);
    $data['expires_at_friendly'] = $expires_at->format('Y-m-d H:i:s');
  }

  $_SESSION['glome']['key'] = $data;
  return $data;
}

function glome_create_user()
{
  $response = glome_post('/users.json');
  $json = $response['body'];

  $data = json_decode($json, true);
  return $data;
}

function glome_get_pairing_code()
{
  if (isset($_SESSION['glome']['id']))
  {
    $id = $_SESSION['glome']['id'];
    $query = '/users/' . $id . '/sync.json';
    $response = glome_post($query);
    $json = $response['body'];
    $data = json_decode($json, true);

    return $data['code'];
  }
}

function glome_get_user_profile()
{
  if (isset($_SESSION['glome']['id']))
  {
    $id = $_SESSION['glome']['id'];
    return $id;

    $query = '/users/' . $id . '.json';
    $response = glome_get($query);

    $json = $response['body'];
    $data = json_decode($json, true);

    return $data['id'];
  }
}

function glome_is_session_paired()
{
  $ret = false;

  if (isset($_SESSION['glome']['glomeid']))
  {
    $id = $_SESSION['glome']['glomeid'];
    $query = '/users/' . $id . '.json';
    $response = glome_get($query);

    $json = $response['body'];
    $data = json_decode($json, true);

    $_SESSION['glome']['id'] = $data['id'];

    $ret = ($data['inwallet'] == 'true');
  }

  return $ret;
}

function glome_track_activity($url)
{
  if (isset($_SESSION['glome']['glomeid']))
  {
    $glomeID = $_SESSION['glome']['glomeid'];
    $query = '/users/' . $glomeID . '/data.json';
    $response = glome_post($query, ['userdata[content]' => 'visit: ' . $url]);
  }
}

function glome_user_login($glomeID)
{
  $query = '/users/login.json';
  $response = glome_post($query, ['user[glomeid]' => $glomeID]);
}

<?php

/**
 * Sanitize Glome API domain
 */
function get_api_domain()
{
  $default_glome_api_server = 'https://api.glome.me/';
  $domain = esc_url_raw(get_option('glome_api_domain'));
  // Todo: is $domain a valid API server?

  if (filter_var($domain, FILTER_VALIDATE_URL) === FALSE)
  {
    $domain = $default_glome_api_server;
    update_option('glome_api_domain', $domain);
  }

  return $domain;
}

/**
 * Generic Glome API GET wrapper
 */
function glome_get($query, $params = false)
{
  if (! $params)
  {
    $params = array();
  }
  $domain = get_api_domain();
  $uid = get_option('glome_api_uid');
  $key = get_option('glome_api_key');
  $url = $domain . $query;
  $url .= "?application[uid]={$uid}&application[apikey]={$key}";
  foreach ($params as $key => $value)
  {
    $url .= '&' . $key . '=' . $value;
  }
  $response = wp_remote_get($url);

  return $response;
}

/**
 * Generic Glome API POST wrapper
 */
function glome_post($query, $params = false)
{
  if (! $params)
  {
    $params = array();
  }
  $domain = get_api_domain();
  $uid = get_option('glome_api_uid');
  $key = get_option('glome_api_key');
  $url = $domain . $query;
  $payload = array(
    'body' => array(
      'application[uid]' => $uid,
      'application[apikey]' => $key,
    ) + $params,
    'timeout' => 15
  );

  $response = wp_remote_post($url, $payload);
  return $response;
}

/**
 * Check if the API credentials are valid
 */
function glome_check_app()
{
  $ret = false;
  $query = '/applications/check/' . base64_encode(get_option('glome_api_uid')) . '.json';
  $response = glome_get($query);

  if (is_array($response) && isset($response['body']))
  {
    $json = $response['body'];

    $data = json_decode($json, true);

    if ($data && array_key_exists('code', $data))
    {
      $ret = ($data['code'] == 200);
    }
  }

  return $ret;
}

/**
 * Create a new Glome ID
 */
function glome_create_user()
{
  $response = glome_post('/users.json');
  $json = $response['body'];

  $ret = json_decode($json, true);
  return $ret;
}

/**
 * Query a Glome ID
 */
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

/**
 * Request a sync code for Glome key authentication
 */
function glome_get_key()
{
  $data = $prev = $now = $expires = null;

  if (array_key_exists('key', $_SESSION['glome']))
  {
    $prev = $_SESSION['glome']['key'];
  }

  if ($prev && isset($prev['expires_at']))
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
    $params = ['synchronization[session]' => session_id()];
    $response = glome_post($query, $params);
    $json = $response['body'];
    $data = json_decode($json, true);
  }

  if (is_array($data) && isset($data['expires_at']) && ! array_key_exists('expires_at_friendly', $data))
  {
    $now = new DateTime('now');
    $expires = new DateTime($data['expires_at']);
    $data['expires_at_friendly'] = $expires->format('Y-m-d H:i:s');
  }

  ($data && array_key_exists('expires_at', $data)) ? $_SESSION['glome']['key'] = $data : $data = null;

  if ($now && $expires)
  {
    $data['countdown'] = $expires->getTimeStamp() - $now->getTimeStamp();
  }

  return $data;
}

/**
 * Request a sync code for pairing
 *
 * @param kind the kind of the sync code
 * @see https://api.glome.me/apidocs/SynchronizationsController.html#method-i-create
 */
function glome_create_pairing_code($kind = 'b')
{
  $ret = null;
  $now = null;
  $glomeid = mywp_current_glomeid();

  if ($glomeid)
  {
    $query = '/users/' . $glomeid . '/sync.json';
    $response = glome_post($query, ['synchronization[kind]' => $kind]);

    if (is_array($response) && isset($response['body']))
    {
      $json = $response['body'];
      $data = json_decode($json, true);

      if (is_array($data) && isset($data['expires_at']) && ! array_key_exists('expires_at_friendly', $data))
      {
        $now = new DateTime('now');
        $expires = new DateTime($data['expires_at']);
        $data['expires_at_friendly'] = $expires->format('Y-m-d H:i:s');
      }

      if ($now && $expires)
      {
        $data['countdown'] = $expires->getTimeStamp() - $now->getTimeStamp();
        $ret = json_encode($data);
      }
    }
  }

  return $ret;
}

/**
 * Post a sync code for pairing
 */
function glome_post_pairing_code($code)
{
  $splits = $ret = null;
  $glomeid = mywp_current_glomeid();

  if ($code)
  {
    $splits = str_split($code, 4);
  }

  if ($glomeid && count($splits) == 3)
  {
    $query = '/users/' . $glomeid . '/sync/pair.json';
    $response = glome_post($query, [
      'pairing[code_1]' => $splits[0],
      'pairing[code_2]' => $splits[1],
      'pairing[code_3]' => $splits[2]
    ]);

    if (isset($response['body']))
    {
      $json = $response['body'];
      $ret = json_decode($json, true);
    }
  }

  return $ret;
}

/**
 * Post a sync code for pairing
 */
function glome_post_unpair($id)
{
  $ret = null;
  $glomeid = mywp_current_glomeid();

  if ($glomeid && $id)
  {
    $query = '/users/' . $glomeid . '/sync/' . $id . '/toggle.json';
    $response = glome_post($query);

    if (isset($response['body']))
    {
      $ret = $response['body'];
      //$ret = json_decode($json, true);
    }
  }

  return $ret;
}

/**
 * Query all brothers of a Glome ID
 */
function glome_get_brothers()
{
  $ret = null;
  $glomeid = mywp_current_glomeid();

  if ($glomeid)
  {
    $query = '/users/' . $glomeid . '/sync.json';
    $response = glome_get($query, ['status' => 'brothers']);

    $json = $response['body'];
    $data = json_decode($json, true);

    // process the original Glome response and take only what we need
    $pair = array();
    foreach($data as $key => $value)
    {

      $pair['id'] = $value['id'];

      $pair['glomeid'] = $value['pair']['glomeid'];
      if ($value['pair']['glomeid'] == $glomeid)
      {
        $pair['glomeid'] = $value['user']['glomeid'];
      }

      $pair['can_unpair'] = 0;
      if ($glomeid == $value['user']['glomeid'] or $glomeid == $value['pair']['glomeid'])
      {
        $pair['can_unpair'] = 1;
      }

      $ret[] = $pair;
    }
  }

  return $ret;
}

/**
 * Request an API access
 */
function glome_request_api_access($details)
{
  $ret = null;

  if (! is_array($details) ||
      ! array_key_exists('servername', $details) ||
      ! array_key_exists('requester', $details) ||
      ! array_key_exists('username', $details) ||
      ! array_key_exists('email', $details) ||
      $details['requester'] != 'Glome Plugin for Wordpress')
  {
    return $ret;
  }

  $query = '/applications/trial.json';
  $response = glome_post($query, [
    'application[servername]' => $details['servername'],
    'application[requester]' => $details['requester'],
    'application[username]' => $details['username'],
    'application[email]' => $details['email'],
  ]);

  if (isset($response['body']))
  {
    $ret = $response['body'];
  }

  return $ret;
}

/**
 * Post a key code for authenticating
 */
function glome_post_key_code($code_part_1, $code_part_2, $code_part_3)
{
  $ret = null;

  $query = '/auth.json';
  $response = glome_post($query, [
    'key[code_1]' => $code_part_1,
    'key[code_2]' => $code_part_2,
    'key[code_3]' => $code_part_3
  ]);

  if (isset($response['body']))
  {
    $ret = $response['body'];
  }

  return $ret;
}

/**
 * Check if the current Glome ID is paired to a wallet
 */
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

/**
 *
 */
function glome_track_activity($url)
{
  $ret = null;
  $glomeid = mywp_current_glomeid();

  if ($glomeid
    && get_option('glome_activity_tracking'))
  {
    $query = '/users/' . $glomeid . '/data.json';
    $response = glome_post($query, ['userdata[content]' => 'visit: ' . $url]);
    $json = $response['body'];
    $ret = json_decode($json, true);
  }

  return $ret;
}

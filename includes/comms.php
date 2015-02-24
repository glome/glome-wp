<?php

function get_glome_query($query, $isGet = false, $params = [])
{
    $domain = get_option('glome_api_domain');
    $uid = get_option('glome_api_uid');
    $key = get_option('glome_api_key');
    $url = $domain . $query;
    if ($isGet) {
        $query = "?application[uid]={$uid}&application[apikey]={$key}";
        $response = wp_remote_get($url . $query);
    } else {
        $response = wp_remote_post($url, array(
            'body' => array(
                'application[uid]' => $uid,
                'application[apikey]' => $key,
            ) + $params
        ));
    }
    return $response;
}

function get_glome_session_id()
{
    $response = get_glome_query('/users.json');
    $json = $response['body'];

    $data = json_decode($json, true);
    return $data['glomeid'];
}


function get_glome_pairing_code()
{
    if (isset($_SESSION['glome']['session'])) {
        $id = $_SESSION['glome']['session'];
        $query = '/users/' . $id . '/sync.json';
        $response = get_glome_query($query);
        $json = $response['body'];
        $data = json_decode($json, true);

        return $data['code'];
    }

}


function get_glome_user_id()
{
    if (isset($_SESSION['glome']['session'])) {
        $id = $_SESSION['glome']['session'];
        $query = '/users/' . $id . '.json';
        $response = get_glome_query($query, true);

        $json = $response['body'];
        $data = json_decode($json, true);

        return $data['id'];
    }
}


function is_glome_session_paired()
{
    if (isset($_SESSION['glome']['session'])) {
        $id = $_SESSION['glome']['session'];
        $query = '/users/' . $id . '.json';
        $response = get_glome_query($query, true);

        $json = $response['body'];
        $data = json_decode($json, true);

        $_SESSION['glome']['id'] = $data['id'];
        return count($data['children']) > 0;
    }

    return false;
}


function glome_track_activity($url)
{

    if (isset($_SESSION['glome']['session'])) {
        $glomeID = $_SESSION['glome']['session'];

        $domain = get_option('glome_api_domain');
        $uid = get_option('glome_api_uid');
        $key = get_option('glome_api_key');

        if (!isset($_SESSION['glome']['cookies'])) {
            glome_user_login($glomeID);
        }

        $response = wp_remote_post($domain . '/users/' . $glomeID . '/data.json', array(
            'body' => array(
                'application[uid]' => $uid,
                'application[apikey]' => $key,
                'userdata[content]' => 'visit: ' . $url,
            ),
            'headers' => array(
                'X-CSRF-Token'  => $_SESSION['glome']['csrf-token'],
            ),
            'cookies' => $_SESSION['glome']['cookies'],
        ));

        $_SESSION['glome']['cookies'] = $response['cookies'];
    }
}


function glome_user_login($glomeID)
{
    $domain = get_option('glome_api_domain');

    $uid = get_option('glome_api_uid');
    $key = get_option('glome_api_key');

    $response = wp_remote_post($domain . '/users/login.json', array(
        'body' => array(
            'application[uid]' => $uid,
            'application[apikey]' => $key,
            'user[glomeid]' => $glomeID,
        ),
    ));

    $_SESSION['glome']['csrf-token'] = $response['headers']['x-csrf-token'];
    $_SESSION['glome']['cookies'] = $response['cookies'];
}

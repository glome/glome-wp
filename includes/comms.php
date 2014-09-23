<?php

function get_glome_query($query, $isGet = false)
{
    $domain = get_option('glome_api_domain');
    $url = $domain . $query;
    if ($isGet) {
        $uid = 'me.glome.wp';
        $key = 'fb313f1e3d434003cdb9a3337aab3041';
        $query = "?application[uid]={$uid}&application[apikey]={$key}";
        $response = wp_remote_get( $url . $query );
    } else {
        $response = wp_remote_post( $url , array(
            'body' => array(
                'application[uid]' => 'me.glome.wp',
                'application[apikey]' => 'fb313f1e3d434003cdb9a3337aab3041',
            )
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
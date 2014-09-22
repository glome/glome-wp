<?php

function get_glome_post($query)
{
    $domain = get_option('glome_api_domain');
    $url = $domain . $query;
    $response = wp_remote_post( $url , array(
        'body' => array(
            'application[uid]' => 'me.glome.wp',
            'application[apikey]' => 'fb313f1e3d434003cdb9a3337aab3041',
        )
    ));
    return $response;
}

function get_glome_session_id()
{
    $response = get_glome_post('/users.json');
    $json = $response['body'];

    $data = json_decode($json, true);
    return $data['glomeid'];
}


function get_glome_pairing_code()
{

    if (isset($_SESSION['glome']['session'])) {
        $id = $_SESSION['glome']['session'];
        $query = '/users/' . $id . '/sync.json';
        $response = get_glome_post($query);
        $json = $response['body'];
        $data = json_decode($json, true);

        return $data['code'];
    }

}

function is_glome_session_paired()
{
    return true;
}
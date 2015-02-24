<?php

function glome_user_exists($id)
{
    $user = get_user_by('login',  glome_get_username($id));
    return $user !== false;
}


function glome_get_username($id)
{
    return 'GlomeAnonymous' . $id;
}

function glome_create_user($id)
{
    $userdata = array(
        'user_login'  => glome_get_username($id),
        'user_url'    => 'https://glome.me',
        'user_pass'   => glome_random_string(32),
        'role'        => 'subscriber',
    );

    wp_insert_user($userdata);
}


function glome_random_string($length) {
    $list = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $result .= $list[rand(0, strlen($list) - 1)];
    }
    return $result;
}

function glome_login_user($id)
{
    $username = glome_get_username($id);

    if ( ! is_user_logged_in() ) {
        $user = get_user_by('login', $username);
        wp_set_current_user( $user->ID, $user->get('user_login') );
        wp_set_auth_cookie( $user->ID );
        do_action( 'wp_login', $user->get('user_login') );
    }
}
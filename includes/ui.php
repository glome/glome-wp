<?php

function glome_login_render_login_form ($source, $args = array ())
{
    //Get the current user
    $current_user = wp_get_current_user ();


    //Check if logged in
    if (!empty ($current_user->ID) AND is_numeric ($current_user->ID))
    {
        //Current user
        $user_id = $current_user->ID;
        var_dump($user_id);
    }
    else
    {


        include __DIR__ . '/../templates/login.php';
    }
}



function glome_add_scripts ()
{
    $filepath = plugins_url('/glome-login/assets/js/script.js');
    wp_enqueue_script('glome-script', $filepath, false, '0.1', true);
    wp_localize_script( 'glome-script', 'glome_scope', array(
        'pipe' => admin_url( 'admin-ajax.php' ),
        'state' => 'guest',
    ));
}

add_action( 'wp_enqueue_scripts', 'glome_add_scripts');


function glome_ajax_challenge()
{
    $code = get_glome_pairing_code();
    echo json_encode(str_split($code, 4));
    exit;
}

add_action( 'wp_ajax_nopriv_challenge', 'glome_ajax_challenge' );
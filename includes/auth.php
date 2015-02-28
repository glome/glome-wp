<?php

function mywp_random_string($length)
{
  $result = '';
  $list = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

  for ($i = 0; $i < $length; $i++)
  {
    $result .= $list[rand(0, strlen($list) - 1)];
  }

  return $result;
}

function mywp_user_exists($id)
{
  $user = get_user_by('login', $id);
  return $user !== false;
}

function mywp_create_user($id)
{
  $userdata = array(
    'role'        => 'subscriber',
    'user_url'    => 'http://glome.me',
    'user_email'  => $id . '@glome.me',
    'user_login'  => $id,
    'user_pass'   => mywp_random_string(32),
    'display_name' => 'Anonymous',
    'user_nicename' => 'Anonymous'
  );

  wp_insert_user($userdata);
}

function mywp_login_user($id)
{
  if ( ! is_user_logged_in() )
  {
    $user = get_user_by('login', $id);
    if ($user !== false)
    {
      wp_set_current_user($user->ID, $user->get('user_login'));
      wp_set_auth_cookie($user->ID);
      do_action('wp_login', $user->get('user_login'));
    }
  }
}
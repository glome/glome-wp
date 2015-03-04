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

function mywp_create_user($wpid, $glomeid)
{
  $userdata = array(
    'role'        => 'subscriber',
    'user_url'    => 'http://glome.me',
    'user_email'  => $wpid . '@glome.me',
    'user_login'  => $wpid,
    'user_pass'   => mywp_random_string(32),
    'display_name' => 'Anonymous',
    'user_nicename' => 'Anonymous'
  );

  if (wp_insert_user($userdata) && $glomeid)
  {
    $user = get_user_by('login', $wpid);
    add_user_meta($user->ID, 'glomeid', $glomeid);
  }
}

function mywp_login_user($id, $glomeid)
{
  if (! is_user_logged_in())
  {
    $user = get_user_by('login', $id);
    if ($user !== false)
    {
      wp_set_current_user($user->ID, $user->get('user_login'));
      wp_set_auth_cookie($user->ID);

      // just in case the user has no glomeid meta tag yet
      $current_glomeid = mywp_current_glomeid();
      if ($current_glomeid == null && $glomeid)
      {
        add_user_meta($user->ID, 'glomeid', $glomeid);
      }

      do_action('wp_login', $user->get('user_login'));
    }
  }
}

function mywp_current_glomeid()
{
  $ret = null;

  if (is_user_logged_in())
  {
    $current_user = wp_get_current_user();

    if (isset($current_user) && is_a($current_user, 'WP_User'))
    {
      // check glomeid meta info
      $ret = get_user_meta($current_user->ID, 'glomeid', true);
      if (is_array($ret))
      {
        // error
        var_dump($ret);
        exit;
      }
    }
  }

  return $ret;
}
<?php

//if the resource is not published then die.
if ('publish' != $post->post_status) {
  die('<h1 style="text-align: center; font-family: arial; margin-top: 3em;">This resource is not published.</h1>');
}

//if the resource is gated then send them back to the gate
if (! (dclmn_auth('cp') || current_user_can('edit_others_posts'))) {
  $out = '';
  $out .= '<title>DCLMN | Forbidden File</title>';
  $out .= '<div style="text-align: center; font-family: tahoma;">';
  $out .= '<br>';
  $out .= '<p><a href="'. home_url() .'"><img src="'. get_stylesheet_directory_uri() .'/images/dclmn-alt-3.png" style="height: 160px; display: inline-block;"></a></p>';
  $out .= '<h2>You do not have access to this document.</h2>';
  $out .= '<p><a href="'. home_url() .'" style="text-decoration: none;">DCLMN.us</a></p>';
  $out .= '</div>';
  echo $out;
  exit;
}

$post = dclmn_get_post(get_the_ID());
$file = get_field('file', $post->ID);
$file_url = str_replace(' ', '%20', $file['url']);
$file_name = basename($file_url);

//set the Content-type header and force a 200. WP think this is 404.
header("HTTP/1.1 200 OK");
header('Content-type: ' . $file['mime_type']);
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Disposition: inline; filename="' . $file_name . '"');

//read and dump out the file.
readfile(get_attached_file($file['ID']));

exit;

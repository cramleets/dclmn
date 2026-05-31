<?php

/*
  Plugin Name: dclmn Polls
 * Description: Clear permalinks after activation.
 */

//require the parent class
//if ( !class_exists( 'dclmn_WP_Theme' ) )
//  return;

$files = array(
    dirname( __FILE__ ) . '/inc/classes/class.dclmn-post.php',
    dirname( __FILE__ ) . '/inc/classes/class.dclmn-poll.php',
    dirname( __FILE__ ) . '/inc/classes/class.dclmn-poll-vote.php',
    dirname( __FILE__ ) . '/inc/classes/class.dclmn-polls.php',
);

foreach ( $files as $file ) {
  if ( file_exists( $file ) ) {
    require_once $file;
  }
}

$dclmn_polls = new DCLMN_Polls();
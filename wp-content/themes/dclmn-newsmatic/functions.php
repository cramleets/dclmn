<?php

if (strpos($_SERVER['HTTP_HOST'], 'dclmn.us') === false) {
  wp_redirect('https://dclmn.us' . $_SERVER['REQUEST_URI'], 301);
  exit;
}

require_once dirname(__FILE__) . '/inc/functions.dclmn.php';
require_once dirname(__FILE__) . '/inc/classes/class.dclmn.php';
require_once dirname(__FILE__) . '/inc/classes/class.dclmn-acf.php';
require_once dirname(__FILE__) . '/inc/classes/class.dclmn-populator.php';
require_once dirname(__FILE__) . '/inc/classes/class.widget-rotating-quotes.php';

require_once dirname(__FILE__) . '/inc/activate.php';

$dclmn = new DCLMN();
$dclmn_acf = new DCLMN_ACF();
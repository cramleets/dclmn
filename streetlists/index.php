<?php

$path = $_SERVER['DOCUMENT_ROOT'] .'/wp-content/uploads/logs/streetlists';
if (!is_dir($path)) {
  mkdir($path, 0755, true);
}

$file = $path .'/streetlists.log';

$line = "";
$line .= date( 'Y-m-d H:i:s' ) . "\t";
$line .= $_SERVER['REMOTE_ADDR']. "\t";
$line .= $_SERVER['HTTP_USER_AGENT']. "\n";

file_put_contents($file, $line, FILE_APPEND | LOCK_EX); 

header('Location: https://www.dclmnsl.org/production/streetlists/login.php');
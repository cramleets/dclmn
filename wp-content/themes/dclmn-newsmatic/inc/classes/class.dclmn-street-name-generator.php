<?php

class DCLMN_LM_Street_Name_Generator {
  function get_prefixes() {
    return [
      'Wyn',
      'Nar',
      'Bryn',
      'Wynne',
      'Ard',
    ];
  }

  function get_suffixes() {
    return [
      'berth',
      'lyn',
      ' Mawr',
      ' Ford',
    ];
  }

  function get_addendums() {
    return [
      'Street',
      'Lane',
      'Way',
      'Avenue',
    ];
  }

  function rando($array) {
    return $array[array_rand($array)];
  }

  function build_street_name() {
    $prefix = $this->rando($this->get_prefixes());
    $suffix = $this->rando($this->get_suffixes());
    $addendum = $this->rando($this->get_addendums());

    while (strtolower(trim($suffix)) == strtolower(trim($prefix))) {
      $suffix = $this->rando($this->get_suffixes());
    }

    //maybe lowercase the suffix for more randomization
    if ((bool) rand(0, 1)) {
      $suffix = trim(strtolower($suffix));
    }

    $name = $prefix . $suffix . ' ' . $addendum;
    
    $this->log($name);

    return $name;
  }

  function log($name) {
    $path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/logs/street-names';
    if (!is_dir($path)) {
      mkdir($path, 0755, true);
    }

    $file = $path . '/street-names.log';

    $line = "";
    $line .= date('Y-m-d H:i:s') . "\t";
    $line .= $name ."\t";
    $line .= $_SERVER['REMOTE_ADDR'] . "\t";
    $line .= $_SERVER['HTTP_USER_AGENT'] . "\n";

    file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
  }
}

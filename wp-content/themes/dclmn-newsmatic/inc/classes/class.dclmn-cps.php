<?php

class DCLMN_CPS {
  function __construct() {
    add_filter('rewrite_rules_array', array($this, 'create_rewrite_rules'), 1000);
    add_filter('query_vars', array($this, 'local_query_vars'));
    add_filter('template_include', array($this, 'template_include'));
  }

  function create_rewrite_rules($rules) {
    $newRules = array();
    $newRules += array('cp/?$' => 'index.php?cp=1');
    //add the new rules to the existing rules and return
    $newRules = $newRules + $rules;
    return $newRules;
  }

  function local_query_vars($vars) {
    $vars[] = 'cp';
    return $vars;
  }

  function template_include($template) {
    if (get_query_var('cp')) {
      return get_stylesheet_directory() . '/cp.php';
    }
    return $template;
  }
}

<?php

class DCLMN_User {
  var $ID;
  var $first_name;
  var $last_name;
  var $email;
  var $phone;
  var $precinct;
  var $street_address_1;
  var $street_address_2;
  var $street_address_3;
  var $city;
  var $state;
  var $zip;
  var $hide_email_address;
  var $positions;
  var $post_type;
  var $cp;

  function __construct($user_id) {
    foreach (dclmn_get_post($user_id) as $k => $v) {
      $this->$k = $v;
    }

    $this->set_cp();
    $this->set_positions();
  }

  function is_cp() {
    return !empty($this->cp);
  }

  function is_exec() {
    return count($this->positions);
  }

  function set_cp() {
    global $wpdb;

    $sql = $wpdb->prepare("SELECT p.*
    FROM {$wpdb->posts} p
    WHERE p.post_status = 'publish'
    AND p.post_type IN ('committee_person')
    AND EXISTS (
        SELECT 1
        FROM {$wpdb->postmeta} pm
        WHERE pm.post_id = p.ID
        AND pm.meta_key IN ('email')
        AND pm.meta_value = %s
    ) LIMIT 1", $this->email);

    if ($row = $wpdb->get_row($sql)) {
      $this->cp = new DCLMN_Position_User($row->ID);
      $this->cp->set_precinct();
    }
  }

  function set_positions() {
    global $wpdb;

    $sql = $wpdb->prepare("SELECT p.*
    FROM {$wpdb->posts} p
    WHERE p.post_status = 'publish'
    AND p.post_type IN ('committee-position')
    AND EXISTS (
        SELECT 1
        FROM {$wpdb->postmeta} pm
        WHERE pm.post_id = p.ID
        AND pm.meta_key IN ('email')
        AND pm.meta_value = %s
    )", $this->email);

    $postitions = [];
    foreach ($wpdb->get_results($sql) as $row) {
      $postitions[] = new DCLMN_Position_User($row->ID);
    }

    $this->positions = $postitions;
  }

  function get_positions() {
    return $this->positions;
  }

  function get_email() {
    return $this->email;
  }

  function get_phone() {
    return $this->phone;
  }

  function get_address() {
    $out = '';

    $out .= ($this->street_address_1) ? $this->street_address_1 . '<br>' : '';
    $out .= ($this->street_address_2) ? $this->street_address_2 . '<br>' : '';
    $out .= ($this->street_address_3) ? $this->street_address_3 . '<br>' : '';

    $out .= ($this->city) ? $this->city : '';
    if ($this->state) {
      if ($this->city) $out .= ', ';
      $out .= $this->state;
    }
    if ($this->zip) {
      if ($this->city || $this->state) $out .= ' ';
      $out .= $this->zip;
    }

    return $out;
  }

  function get_voters() {
    if (!$this->is_cp()) return [];

    ini_set('memory_limit', '-1');
    global $wpdb;

    $precinct = $this->get_precinct();

    $sql = 'SELECT * FROM streetlists.mcdc_data WHERE precinct=%d';
    $sql = $wpdb->prepare($sql,  $this->get_precinct()->precinct_number);
    $results = $wpdb->get_results($sql);

    $voters = [];
    foreach ($results as $r) {
      $voters[$r->political_party][] = $r;
    }

    return $voters;
  }

  function email_address_is_hidden() {
    return $this->hide_email_address;
  }
}

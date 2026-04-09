<?php

class DCLMN_User {
  var $ID;
  var $first_name;
  var $last_name;
  var $public_email;
  var $phone;
  var $precinct;
  var $street_address_1;
  var $street_address_2;
  var $street_address_3;
  var $city;
  var $state;
  var $zip;
  var $hide_email_address;

  function __construct($user_id) {
    foreach (dclmn_get_post($user_id) as $k => $v) {
      $this->$k = $v;
    }
  }

  function get_email() {
    return $this->get_public_email();
  }

  function get_public_email() {
    return $this->public_email;
  }

  function get_phone() {
    return $this->phone;
  }

  function get_precinct() {
    return ($this->precinct) ? dclmn_get_post($this->precinct) : false;
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

  function is_exec() {
    $query = new WP_Query(array(
      'post_type'    => 'committee-position',
      'meta_key' => 'email',
      'meta_value' => $this->get_email(),
      'posts_per_page' => 1,
      'post_status'    => 'any',
    ));

    return $query->have_posts();
  }

  function get_voters() {
    ini_set('memory_limit', '-1');
    global $wpdb;

    $precinct = $this->get_precinct();

    $sql = 'SELECT * FROM streetlists.mcdc_data WHERE precinct=%d';
    $sql = $wpdb->prepare($sql, $precinct->precinct_number);
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

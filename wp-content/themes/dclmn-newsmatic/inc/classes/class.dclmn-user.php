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

  function __construct($user_id) {
    foreach (dclmn_get_post($user_id) as $k => $v) {
      $this->$k = $v;
    }

    $this->set_positions();
    $this->set_precinct();
  }

  function is_cp() {
    return !empty($this->precinct);
  }

  function is_exec() {
    return count($this->positions);
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
      $postitions[] = dclmn_get_post($row->ID);
    }

    $this->positions = $postitions;
  }

  function set_precinct() {
    if (!empty($this->precinct)) return;

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
    )", $this->email);

    $posts =$wpdb->get_results($sql);
    if (count($posts)) {
      $precinct = get_post_meta($posts[0]->ID, 'precinct', true);
      if (!empty($precinct)) {
        $this->precinct = dclmn_get_post($precinct);
      }
    }
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

  function get_mailbox() {
    if ($this->is_cp()) {
      $mailbox = $this->get_precinct()->post_title;
      $mailbox = str_replace('Narberth ', 'N-', $mailbox);
      $mailbox = str_replace('Lower Merion ', '', $mailbox);
    } else {
      $mailbox = $this->mailbox;
    }

    $mailbox = strtolower($mailbox) . '@dclmn.us';

    return $mailbox;
  }
}

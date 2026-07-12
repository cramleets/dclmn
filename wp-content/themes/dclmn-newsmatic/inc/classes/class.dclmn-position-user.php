<?php

class DCLMN_Position_User extends DCLMN_Post {

  var $email;
  var $phone;
  var $mailbox;
  var $hide_email_address;
  var $precinct;
  var $post_type;

  function get_email() {
    return $this->email;
  }

  function get_phone() {
    return $this->phone;
  }

  function email_address_is_hidden() {
    return $this->hide_email_address;
  }

  function is_cp() {
    return ('committee_person' == $this->post_type);
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

  function get_precinct() {
    return ($this->precinct) ? dclmn_get_post($this->precinct) : false;
  }

  function set_precinct() {
    $this->precinct = dclmn_get_post($this->precinct);
  }
}

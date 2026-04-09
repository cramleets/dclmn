<?php

class DCLMN_Cpanel_API extends Cpanel_API {
  function get_cp_mailbox($precinct) {
    $mailbox = $precinct->post_title;
    $mailbox = str_replace('Narberth ', 'N-', $mailbox);
    $mailbox = str_replace('Lower Merion ', '', $mailbox);
    $mailbox = strtolower($mailbox);
    return $mailbox;
  }

  function load_precinct_forwarders() {
    global $dclmn;

    set_time_limit(60 * 15);

    $precincts = $dclmn->get_committee_people_table('raw');

    logger("Load CP Forwarders - START", 'cpanel-api');
    foreach ($precincts as $precinct) {
      foreach ($precinct->committe_people as $person) {
        $vacant = ('vacant' == strtolower($person->first_name));
        if ($vacant) continue;

        $mailbox = $this->get_cp_mailbox($precinct);

        $domain = "dclmn.us";
        $email_1 = "$mailbox@$domain";
        $email_2 = str_replace('-', '_', $email_1);
        $email_3 = str_replace('-', '.', $email_1);
        $destination = strtolower($person->public_email);

        $result = $this->add_forwarder($domain, $email_1, $destination);
        logger("Added CP Forwarder {$domain} {$email_1} {$destination}", 'cpanel-api');

        $result = $this->add_forwarder($domain, $email_2, $destination);
        logger("Added CP Forwarder {$domain} {$email_2} {$destination}", 'cpanel-api');

        $result = $this->add_forwarder($domain, $email_3, $destination);
        logger("Added CP Forwarder {$domain} {$email_3} {$destination}", 'cpanel-api');
      }
    }
    logger("Load CP Forwarders - END", 'cpanel-api');
  }

  function delete_precinct_forwarders() {
    global $dclmn;

    set_time_limit(60 * 15);

    $precincts = $dclmn->get_committee_people_table('raw');

    logger("Delete CP Forwarders - START", 'cpanel-api');
    foreach ($precincts as $precinct) {
      foreach ($precinct->committe_people as $person) {
        $vacant = ('vacant' == strtolower($person->first_name));
        if ($vacant) continue;
        if (!$person->public_email) continue;

        $mailbox = $this->get_cp_mailbox($precinct);

        $domain = "dclmn.us";
        $email_1 = "$mailbox@$domain";
        $email_2 = str_replace('-', '_', $email_1);
        $email_3 = str_replace('-', '.', $email_1);
        $destination = strtolower($person->public_email);

        $result = $this->delete_forwarder($email_1, $destination);
        logger("Deleted CP Forwarder {$email_1} {$destination}", 'cpanel-api');

        $result = $this->delete_forwarder($email_2, $destination);
        logger("Deleted CP Forwarder {$email_2} {$destination}", 'cpanel-api');

        $result = $this->delete_forwarder($email_3, $destination);
        logger("Deleted CP Forwarder {$email_3} {$destination}", 'cpanel-api');
      }
    }
    logger("Delete CP Forwarders - END", 'cpanel-api');
  }

  function load_leadership_forwarders() {
    global $dclmn;

    set_time_limit(60 * 15);
    $leadership = $dclmn->get_leadership();

    logger("Load Leadership Forwarders - START", 'cpanel-api');
    foreach ($leadership as $person) {
      $mailbox = $person->mailbox;

      if (empty($mailbox)) continue;

      $mailbox = sanitize_title(strtolower($mailbox));

      $domain = "dclmn.us";
      $email_1 = "$mailbox@$domain";
      $email_2 = str_replace('-', '_', $email_1);
      $email_3 = str_replace('-', '.', $email_1);
      $destination = strtolower($person->email);

      $result = $this->add_forwarder($domain, $email_1, $destination);
      logger("Added Leadership Forwarder {$domain} {$email_1} {$destination}", 'cpanel-api');

      if ($email_1 != $email_2) {
        $result = $this->add_forwarder($domain, $email_2, $destination);
        logger("Added Leadership Forwarder {$domain} {$email_2} {$destination}", 'cpanel-api');
      }

      if ($email_1 != $email_3) {
        $result = $this->add_forwarder($domain, $email_3, $destination);
        logger("Added Leadership Forwarder {$domain} {$email_3} {$destination}", 'cpanel-api');
      }
    }
    logger("Load Leadership Forwarders - END", 'cpanel-api');
  }

  function delete_leadership_forwarders() {
    global $dclmn;

    set_time_limit(60 * 15);
    $leadership = $dclmn->get_leadership();

    logger("Delete Leadership Forwarders - START", 'cpanel-api');
    foreach ($leadership as $person) {
      $mailbox = $person->mailbox;

      if (empty($mailbox)) continue;

      $domain = "dclmn.us";
      $email_1 = "$mailbox@$domain";
      $email_2 = str_replace('-', '_', $email_1);
      $email_3 = str_replace('-', '.', $email_1);
      $destination = strtolower($person->email);

      $result = $this->delete_forwarder($email_1, $destination);
      logger("Deleted Leadership Forwarder {$email_1} {$destination}", 'cpanel-api');

      if ($email_1 != $email_2) {
        $result = $this->delete_forwarder($email_2, $destination);
        logger("Deleted Leadership Forwarder {$email_2} {$destination}", 'cpanel-api');
      }

      if ($email_1 != $email_3) {
        $result = $this->delete_forwarder($email_3, $destination);
        logger("Deleted Leadership Forwarder {$email_3} {$destination}", 'cpanel-api');
      }
    }
    logger("Delete Leadership Forwarders - END", 'cpanel-api');
  }
}

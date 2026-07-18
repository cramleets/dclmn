<?php

class DCLMN_Cpanel_API extends Cpanel_API {
  var $cpanel_user = 'xuvjwbte';
  var $api_token = 'XECE9OGW23OCRBCFAWBCTMESJH7QG6QD';
  var $host = 'https://gator4253.hostgator.com:2083';

  function get_cp_mailbox($precinct) {
    $mailbox = $precinct->post_title;
    $mailbox = str_replace('Narberth ', 'N-', $mailbox);
    $mailbox = str_replace('Lower Merion ', '', $mailbox);
    $mailbox = strtolower($mailbox);
    return $mailbox;
  }

  function set_existing_forwarders() {
    $forwarders = $this->get_forwarders();
    foreach ($forwarders as $forwarder) {
      $this->existing_forwarders[$forwarder['dest']][] = $forwarder['forward'];
    }
  }

  function get_sorted_forwarders() {
    $forwarders_raw = $this->get_forwarders();

    //sort each entry by key
    foreach ($forwarders_raw as &$forwarder) {
      ksort($forwarder);
    }
    unset($forwarder); // break the reference

    //sort the array by destination
    usort($forwarders_raw, function ($a, $b) {
      $aIsN = str_starts_with(strtolower($a['dest']), 'n');
      $bIsN = str_starts_with(strtolower($b['dest']), 'n');

      if ($aIsN !== $bIsN) {
        return $aIsN ? -1 : 1;
      }

      return strnatcasecmp($a['dest'], $b['dest']);
    });

    $forwarders = [];
    foreach ($forwarders_raw as $forwarder) {
      $key = (preg_match('/^(?:\d+|\*|n)[-._]\d/', $forwarder['dest'])) ? 'CPs' : 'Exec';
      $forwarders[$key][] = $forwarder;
    }

    return $forwarders;
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
        $fwdemail = strtolower($person->email);

        //if ('marc.steel@gmail.com' != $fwdemail) continue;

        $result = $this->add_forwarder($domain, $email_1, $fwdemail);
        logger("Added CP Forwarder {$domain} {$email_1} {$fwdemail}", 'cpanel-api');

        $result = $this->add_forwarder($domain, $email_2, $fwdemail);
        logger("Added CP Forwarder {$domain} {$email_2} {$fwdemail}", 'cpanel-api');

        $result = $this->add_forwarder($domain, $email_3, $fwdemail);
        logger("Added CP Forwarder {$domain} {$email_3} {$fwdemail}", 'cpanel-api');
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
        if (!$person->email) continue;

        $mailbox = $this->get_cp_mailbox($precinct);

        $domain = "dclmn.us";
        $email_1 = "$mailbox@$domain";
        $email_2 = str_replace('-', '_', $email_1);
        $email_3 = str_replace('-', '.', $email_1);
        $destination = strtolower($person->email);

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
    foreach ($leadership as $position_label => $people) {
      foreach ($people as $person) {
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
    }
    logger("Load Leadership Forwarders - END", 'cpanel-api');
  }

  function delete_leadership_forwarders() {
    global $dclmn;

    set_time_limit(60 * 15);
    $leadership = $dclmn->get_leadership();

    logger("Delete Leadership Forwarders - START", 'cpanel-api');
    foreach ($leadership as $position_label => $people) {
      foreach ($people as $person) {
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

  function delete_existing_forwarders() {
    logger("Delete Existing Forwarders - START", 'cpanel-api');
    $forwarders = $this->get_forwarders();
    foreach ($forwarders as $forwarder) {
      die('is this a forwarder that would be created?');
      $result = $this->delete_forwarder($forwarder['dest'], $forwarder['forward']);
      logger("Deleted CP Forwarder {$forwarder['forward']} {$forwarder['dest']}", 'cpanel-api');
    }
    logger("Delete Existing Forwarders - END", 'cpanel-api');
  }
}

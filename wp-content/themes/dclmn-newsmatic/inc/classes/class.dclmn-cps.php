<?php

class DCLMN_CPS {
  var $cookie_name = 'cp';
  var $nonce_timeout = 60 * 20;
  var $cookie_length = 60;
  var $cookie_path = '/';
  var $cookie_domain;

  function __construct() {
    session_start();
    
    add_action('wp', function () {
      $this->request_action_listeners();
    });

    add_action('wp_ajax_cp_login', [$this, 'ajax_cp_login']);
    add_action('wp_ajax_nopriv_cp_login', [$this, 'ajax_cp_login']);

    $this->cookie_length = time() + 60 * 60 * 24 * 90;
    $this->cookie_domain = $_SERVER['HTTP_HOST'];
  }

  function request_action_listeners() {
    if (!empty($_REQUEST['action'])) {
      if ('cp-login' == $_REQUEST['action']) {
        $this->login_from_link($_REQUEST);
      }
      if ('cp-logout' == $_REQUEST['action']) {
        $this->delete_cookie();
        $_SESSION['dclmn_cp_message'] = 'Goodbye.';
        header('Location: ' . home_url('cp/'));
        exit;
      }
    }
  }

  function create_rewrite_rules($rules) {
    $newRules = array();
    $newRules += array('cp/?$' => 'index.php?cp=1&committee_person=0');
    //add the new rules to the existing rules and return
    $newRules = $newRules + $rules;
    return $newRules;
  }

  function local_query_vars($vars) {
    $vars[] = 'cp';
    return $vars;
  }

  function get_cp_by_email($email) {
    $posts = dclmn_get_posts([
      'post_type'  => 'committee_person',
      'meta_key'   => 'public_email',
      'meta_value' => $email,
      'numberposts' => 1
    ]);

    if (empty($posts)) {
      return false;
    } else {
      return $posts[0];
    }
  }

  function ajax_cp_login() {
    $status = 'fail';
    $message = 'Could not find a CP with that email address.';

    $cp = $this->get_cp_by_email($_POST['email']);

    if (is_object($cp) && 'committee_person' == $cp->post_type) {
      $status = 'success';

      $message = 'Please check your email for a login link.';
      $message .= '<br>In about a minute you will receive an email with a link to log in.';
      $message .= '<br>Links expire in 15 minutes. Be sure to check your junk folder.';

      if ('::1' == $_SERVER['REMOTE_ADDR']) {
        $message .= ' | <a href="' . $this->get_login_url($cp->ID) . '">Login</a>';
      }
      
      wp_mail($cp->public_email, 'DCLMN CP Log In', $this->get_login_email_content($cp));
    }

    $result = [
      'status' => $status,
      'message' => $message,
    ];
    die(json_encode($result));
  }

  function get_login_email_content($cp) {
    $url = $this->get_login_url($cp->ID);

    $out = '';

    $out .= 'Please use this link to log in. It will expire in ' . $this->nonce_timeout . ' minutes';
    $out .= $url;

    return $out;
  }

  function get_login_url($cp_id) {
    global $nmi;

    $cp = dclmn_get_post($cp_id);

    //get the participant's url
    $url = home_url('cp/');

    //what to feed the nonce
    $action_prefix = 'dclmn_login_';
    $salt = $cp->public_email;

    //get the nonce data
    $nonce_data = dclmn_nonce_create($action_prefix, $salt, $this->nonce_timeout);

    //access the nonce data
    $email_hashed = $nonce_data['salt_hash'];
    $email_hash_encoded = rawurlencode($email_hashed);

    //add some query string args
    $url = add_query_arg('action', 'cp-login', $url);
    $url = add_query_arg('cp', $cp->ID, $url);
    $url = add_query_arg('e', $email_hash_encoded, $url);
    $url = add_query_arg('n', $nonce_data['nonce'], $url);
    $url = add_query_arg('cb', uniqid(), $url);

    return $url;
  }

  function login_from_link($args) {
    global $napco_cpt_accounts;

    $result = [
      'status' => 'fail',
    ];

    $email_hashed = rawurldecode($args['e']);
    $email = unserialize(base64_decode($email_hashed));

    $salt = $email; // Replace with the same salt used during creation
    $action_prefix = 'dclmn_login_';
    $provided_nonce = $_GET['n']; // Example: nonce from a query parameter

    $nonce_verify = dclmn_nonce_verify($action_prefix, $provided_nonce, $salt, $this->nonce_timeout);

    //is there a cp id?
    if (!isset($args['cp'])) {
      $result['msg'] = 'No cp id set.';
    }

    //is there a token?
    elseif (!isset($args['n'])) {
      $result['msg'] = 'No token set.';
    }

    //is the nonce valid.
    elseif (!$nonce_verify) {
      $result['msg'] = 'Invalid or Expired Token.';
    }

    //basics are covered, what else?
    else {

      //get the participant id
      $cp_id = $args['cp'];

      //get the participant
      $cp = dclmn_get_post($cp_id);

      //do the emails match?
      if (!$cp || !$cp->ID || 'committee_person' != $cp->post_type) {
        $result['msg'] = 'Invalid CP.';
      }

      //do the emails match?
      elseif (base64_encode(serialize(($cp->public_email))) != $args['e']) {
        $result['msg'] = 'Mismatchd emails.';
      }

      //can this participant type log in?
      else {
        //made it!! log 'em in! and send them on.
        //set a welcome message to appear on the schedule
        $_SESSION['dclmn_cp_message'] = 'Welcome ' . $cp->first_name . '.';
        $this->set_cookie($cp->ID);

        $url = home_url('cp/');

        header('Location: ' . $url);
        exit;
      }
    }

    //if they made it here it's a failure. store the result to session.
    $_SESSION['dclmn_cp_message'] = $result['msg'];

    $url = strtok($_SERVER["REQUEST_URI"], '?');
    header('Location: ' . $url);
    exit;
  }

  /**
   * Log them in.
   * @param int $account_id
   */
  function set_cookie($cp_id) {
    $cookie_value = $this->encodeData(['cp_id' => $cp_id]);
    $cookie_expiry = time() + (60 * 60 * 10);
    $cookie_domain = $_SERVER['HTTP_HOST'];

    //Set the cookie on .napco.com;
    setcookie($this->cookie_name, $cookie_value, [
      'expires' =>   $cookie_expiry,
      'path' =>      '/',
      'domain' =>    $cookie_domain,
      'secure' =>    true,    // Cookie is sent over HTTPS
      'httponly' =>  true, // Cannot be accessed by JavaScript (yeah?? - marc)
      'samesite' =>  'None', // Cross-domain cookies require SameSite=None
    ]);

    //set for immediate use.
    $_COOKIE[$this->cookie_name] = $cookie_value;
  }

  /**
   * Log them out.
   */
  function delete_cookie() {
    setcookie($this->cookie_name, false, time() - 3600, $this->cookie_path, $this->cookie_domain);
    unset($_COOKIE[$this->cookie_name]);
  }

  function encodeData($data) {
    return base64_encode(serialize($data));
  }

  function decodeData($data) {
    return unserialize(base64_decode($data));
  }
}

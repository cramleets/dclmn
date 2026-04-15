<?php

class DCLMN_Users {
  var $cookie_name = 'dclmn';
  var $nonce_timeout = 60 * 20;
  var $cookie_length = 60;
  var $cookie_path = '/';
  var $cookie_domain;

  function __construct() {
    session_start();

    add_action('wp', function () {
      $this->request_action_listeners();
    });

    add_action('wp_ajax_user_login', [$this, 'ajax_user_login']);
    add_action('wp_ajax_nopriv_user_login', [$this, 'ajax_user_login']);
    add_action('template_redirect', [$this, 'template_redirect']);

    $this->cookie_length = time() + MONTH_IN_SECONDS;
    $this->cookie_domain = $_SERVER['HTTP_HOST'];
  }

  function template_redirect($template) {
    global $post;
    if (is_object($post) && 'dclmn-contacts' == $post->post_name && !dclmn_auth('exec')) {
      wp_redirect(home_url());
    }
    if (is_object($post) && 'precinct-voters' == $post->post_name && !dclmn_auth('cp')) {
      wp_redirect(home_url());
    }
    if (is_object($post) && 'cps' == $post->post_name && !dclmn_auth('cp')) {
      wp_redirect(home_url());
    }
    return $template;
  }

  function request_action_listeners() {
    if (!empty($_REQUEST['action'])) {
      if ('cp-login' == $_REQUEST['action']) {
        $this->login_from_link($_REQUEST);
      }
      if ('cp-logout' == $_REQUEST['action']) {
        $this->delete_cookie();
        $_SESSION['dclmn_user_message'] = 'Goodbye.';
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

  function get_user_by_email($email) {
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

  function ajax_user_login() {
    $status = 'fail';
    $message = 'Could not find a CP with that email address.';

    $dclmn_user = $this->get_user_by_email($_POST['email']);

    $this->log('request-made', $this->encodeData($_POST['email']));

    if (is_object($dclmn_user) && 'committee_person' == $dclmn_user->post_type) {


      if (0 && 'marc.steel@gmail.com' != $dclmn_user->public_email) {
        $status = 'fail';
        $message = 'Undergoing maintenance.';
        $message = 'Not open for business.';
      } else {
        $status = 'success';

        $message = '';
        //$message .= 'Please check your email for a login link.<br>';
        $message .= 'In about a minute you will receive an email with a link to log in.<br>';
        $message .= 'Links expire in 15 minutes. Be sure to check your junk folder.';

        if ('::1' == $_SERVER['REMOTE_ADDR']) {
          $message .= '<br><a href="' . $this->get_login_url($dclmn_user->ID) . '">Login</a>';
        }

        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail($dclmn_user->public_email, 'DCLMN CP Log In', $this->get_login_email_content($dclmn_user), $headers);

        $this->log('request-sent', $this->encodeData($dclmn_user->public_email, $dclmn_user->ID));
      }
    }

    $result = [
      'status' => $status,
      'message' => $message,
    ];
    die(json_encode($result));
  }

  function get_login_email_content($dclmn_user) {
    $url = $this->get_login_url($dclmn_user->ID);
    $minutes = ($this->nonce_timeout / 60) - 5;

    $out = '';
    $out .= '</html>';
    $out .= '<head>';
    $out .= '</head>';
    $out .= '<body bgcolor="#cccccc" style="background-color: #cccccc;">';
    $out .= '<table cellpadding="20" cellspacing="0" border="0"><tr><td>';
    $out .= '<div style="background-color: #ffffff; font-family: verdana, sans-serif; font-size: 14px; max-width: 600px; word-break: break-all; border: 1px solid #000; padding: 10px;">';
    $out .= '<a href=""><img src="' . dclmn_thumb(get_stylesheet_directory_uri() . '/images/dclmn-alt-3.png', ['width' => 200, 'height' => 116]) . '" width="200" height="116"></a>';
    $out .= '<p style="font-size: 20px;"><a href="' . $url . '" style="color: #031588"><strong>Click Here To Log In.</strong></a>';
    $out .= '<p><em>This link  will expire in ' . $minutes . ' minutes.</em></p>';
    $out .= '<br>';
    $out .= '<p><strong>Or you can copy and paste this link:</strong><br>' . $url . '</p>';
    $out .= '<br>';
    $out .= '<p style="font-size: 10px;">Email sent ' . current_time('F j, Y \a\t g:ia') . '.</p>';
    $out .= '</div>';
    $out .= '</td></tr></table>';
    $out .= '</body>';
    $out .= '</html>';

    return $out;
  }

  function get_login_url($user_id) {
    global $nmi;

    $dclmn_user = dclmn_get_post($user_id);

    //get the participant's url
    $url = home_url('cp/');

    //what to feed the nonce
    $action_prefix = 'dclmn_login_';
    $salt = $dclmn_user->public_email;

    //get the nonce data
    $nonce_data = dclmn_nonce_create($action_prefix, $salt, $this->nonce_timeout);

    //access the nonce data
    $email_hashed = $nonce_data['salt_hash'];
    $email_hash_encoded = rawurlencode($email_hashed);

    //add some query string args
    $url = add_query_arg('action', 'cp-login', $url);
    $url = add_query_arg('cp', $dclmn_user->ID, $url);
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
      $user_id = $args['cp'];

      //get the participant
      $dclmn_user = dclmn_get_post($user_id);

      //do the emails match?
      if (!$dclmn_user || !$dclmn_user->ID || 'committee_person' != $dclmn_user->post_type) {
        $result['msg'] = 'Invalid CP.';
      }

      //do the emails match?
      elseif (base64_encode(serialize(($dclmn_user->public_email))) != $args['e']) {
        $result['msg'] = 'Mismatchd emails.';
      }

      //do the emails match?
      elseif (0 && $dclmn_user->public_email != 'marc.steel@gmail.com') {
        $result['msg'] = 'Not yet.';
      }

      //can this participant type log in?
      else {
        //made it!! log 'em in! and send them on.
        //set a welcome message to appear on the schedule
        $_SESSION['dclmn_user_message'] = 'Welcome ' . $dclmn_user->first_name . '.';
        $this->set_cookie($dclmn_user->ID, $email_hashed);
        $this->log('login', $email_hashed, $dclmn_user->ID);
        
        $headers = [];//array('Content-Type: text/html; charset=UTF-8');
        wp_mail('marc.steel@gmail.com', 'DCLMN CP Log In!', print_r($dclmn_user, 1), $headers);

        $url = home_url('cp/');

        header('Location: ' . $url);
        exit;
      }
    }

    //if they made it here it's a failure. store the result to session.
    $_SESSION['dclmn_user_message'] = $result['msg'];

    $url = strtok($_SERVER["REQUEST_URI"], '?');
    header('Location: ' . $url);
    exit;
  }

  function get_dclmn_user($user_id = false) {
    $dclmn_user = false;
    if (!$user_id && !empty($_COOKIE[$this->cookie_name])) {
      $cookie = $this->decodeData($_COOKIE[$this->cookie_name]);
      $user_id = $cookie['user_id'];
    }

    if ($user_id) {
      if (empty($cookie['email_hashed'])) {
        die('Something was wrong with the cookie.');
        exit;
      }

      $dclmn_user = new DCLMN_User($user_id);
      if ($cookie['email_hashed'] != $this->encodeData($dclmn_user->get_email())) {
        die('User email mismatch.');
        exit;
      }
    }

    return $dclmn_user;
  }

  /**
   * Log them in.
   * @param int $account_id
   */
  function set_cookie($user_id, $email_hashed) {
    $cookie_value = $this->encodeData(['user_id' => $user_id, 'email_hashed' => $email_hashed]);
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

  function log($action, $email_hashed=false, $dclmn_user_id=false) {
    $path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/logs/cp-logins';
    if (!is_dir($path)) {
      mkdir($path, 0755, true);
    }

    $file = $path . '/cp-logins.log';

    $line = "";
    $line .= date('Y-m-d H:i:s') . "\t";
    $line .= $_SERVER['REMOTE_ADDR'] . "\t";
    $line .= $action ."\t";
    $line .= $email_hashed ."\t";
    $line .= $dclmn_user_id ."\t";
    $line .= $_SERVER['HTTP_USER_AGENT'] . "\n";

    file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
  }
}

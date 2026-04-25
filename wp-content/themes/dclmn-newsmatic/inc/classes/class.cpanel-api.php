<?php


class Cpanel_API {
  var $cpanel_user;
  var $api_token;
  var $host;
  var $domain;
  var $email;
  var $destination;

  function __construct() {
  }

  function set_domain($domain) {
    $this->domain = $domain;
  }

  function set_email($email) {
    $this->email = $email;
  }

  function set_destication($destination) {
    $this->destination = $destination;
  }

  function get_forwarders() {
    return $this->call("/execute/Email/list_forwarders");
  }

  function add_forwarder($domain, $email, $destination, $fwdopt = 'fwd') {
    $fields = [
      "domain"   => $domain,
      "email"    => $email,
      "fwdemail" => $destination,
      "fwdopt"   => $fwdopt,
    ];
    return $this->call("/execute/Email/add_forwarder", $fields);
  }

  function delete_forwarder($email, $destination) {
    $fields = [
      "address"    => $email,
      "forwarder" => $destination
    ];
    return $this->call("/execute/Email/delete_forwarder", $fields);
  }

  function call($path, $fields = []) {
    $url = $this->host . $path . '?' . http_build_query($fields);

    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => [
        "Authorization: cpanel {$this->cpanel_user}:{$this->api_token}",
      ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($response === false) {
      die('Curl error: ' . curl_error($ch));
    }

    if ($httpCode === 200) {
      $data = json_decode($response, true);
      if (1 !== $data['status']) {
        logger($data['errors'], 'cpanel-api-errors', 'error');
        pobj($data['errors']);
      } else {
        return $data['data'];
      }
    } else {
      logger("$httpCode: $response", 'cpanel-api-errors', 'error');
      die("Error ($httpCode): $response");
    }
  }
}

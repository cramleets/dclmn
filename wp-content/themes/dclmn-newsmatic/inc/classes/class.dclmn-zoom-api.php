<?php

//ZOOM API
class DCLMN_Zoom_API {

  var $accountId = 'UVq3OKLMQiSS_4RcIDYZ0Q';
  var $clientId = 'OMLGDnbfRuCXmDV1BGjKUw';
  var $clientSecret = 'rRBNi9OraVSUTy5oCvtbrLmUt7nbS6Hr';
  var $secretToken = 'OuNczpvISIqdFnYbG6ZAmg';

  function __construct() {
  }

  function call($path) {
    $url = "https://zoom.us/oauth/token";

    // Prepare POST fields
    $postFields = http_build_query([
      'grant_type' => 'account_credentials',
      'account_id' => $this->accountId
    ]);

    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => $postFields,
      CURLOPT_HTTPHEADER => [
        'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
        'Content-Type: application/x-www-form-urlencoded'
      ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($response === false) {
      die('Curl error: ' . curl_error($ch));
    }

    curl_close($ch);

    if ($httpCode === 200) {
      $data = json_decode($response, true);
      //echo "Access Token: " . $data['access_token'] . "\n";
      //echo "Expires In: " . $data['expires_in'] . " seconds\n";
    } else {
      die("Error ($httpCode): $response");
    }

    // Replace with your Zoom OAuth access token
    $accessToken =  $data['access_token'];

    // Replace with your Zoom user ID or email (can use "me" for the authenticated user)
    $userId = 'me';

    // Initialize cURL
    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => "https://api.zoom.us/v2/users/{$userId}/$path",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json",
        "Accept: application/json"
      ]
    ]);

    // Execute request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($response === false) {
      die('Curl error: ' . curl_error($ch));
    }

    curl_close($ch);

    return json_decode($response, true);
  }

  function get_meetings($force = false) {
    $transient_name = 'zoom_meetings';
    if ($force || !$return = get_transient($transient_name)) {
      $return = $this->call('meetings');
      if (isset($return['meetings'])) $return = $return['meetings'];

      $return = base64_encode(serialize($return));
      set_transient($transient_name, $return, 60 * 60 * 24);
    }

    $return = unserialize(base64_decode($return));
    return $return;
  }

  function get_webinars($force = false) {
    $transient_name = 'zoom_webinars';
    if ($force || !$return = get_transient($transient_name)) {
      $return = $this->call('webinars');
      if (isset($return['webinars'])) $return = $return['webinars'];

      $return = base64_encode(serialize($return));
      set_transient($transient_name, $return, 60 * 60 * 24);
    }

    $return = unserialize(base64_decode($return));
    return $return;
  }
}

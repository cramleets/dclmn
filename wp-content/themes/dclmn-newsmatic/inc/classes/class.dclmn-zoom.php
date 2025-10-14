<?php

//ZOOM API
class DCLMN_Zoom {

  function __construct() {
    $accountId = 'UVq3OKLMQiSS_4RcIDYZ0Q';
    $clientId = 'OMLGDnbfRuCXmDV1BGjKUw';
    $clientSecret = 'rRBNi9OraVSUTy5oCvtbrLmUt7nbS6Hr';

    $secretToken = 'OuNczpvISIqdFnYbG6ZAmg';

    $url = "https://zoom.us/oauth/token";

    // Prepare POST fields
    $postFields = http_build_query([
      'grant_type' => 'account_credentials',
      'account_id' => $accountId
    ]);

    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => $postFields,
      CURLOPT_HTTPHEADER => [
        'Authorization: Basic ' . base64_encode($clientId . ':' . $clientSecret),
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
      echo "Access Token: " . $data['access_token'] . "\n";
      echo "Expires In: " . $data['expires_in'] . " seconds\n";
    } else {
      echo "Error ($httpCode): $response\n";
    }


    // Replace with your Zoom OAuth access token
    $accessToken =  $data['access_token'];

    // Replace with your Zoom user ID or email (can use "me" for the authenticated user)
    $userId = 'me';

    // Initialize cURL
    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => "https://api.zoom.us/v2/users/{$userId}/webinars",
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
    pobj($response, 1);

    if ($response === false) {
      die('Curl error: ' . curl_error($ch));
    }

    curl_close($ch);

    // Handle response
    if ($httpCode === 200) {
      $data = json_decode($response, true);
      echo "Meetings:\n";
      foreach ($data['meetings'] as $meeting) {
        echo "- {$meeting['topic']} ({$meeting['id']}) on {$meeting['start_time']}\n";
      }
    } else {
      echo "Error ($httpCode): $response\n";
    }
  }
}

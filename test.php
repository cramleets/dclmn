<?php

//phpinfo(); exit;
require(dirname(__FILE__) . '/wp-blog-header.php');

die((new DCLMN_Users())->get_login_url(1195));

exit;



/**
 * 
 * Get all forewarders
 * Loop through all CP's 
 * Find forwarders that don't match current CP and delete
 * Add new one.
 * 
 * Loop through leadership?
 * 
 */


require_once get_theme_file_path() . '/inc/classes/class.cpanel-api.php';
require_once get_theme_file_path() . '/inc/classes/class.dclmn-cpanel-api.php';

$cpanel_user = 'xuvjwbte';
$api_token = "XECE9OGW23OCRBCFAWBCTMESJH7QG6QD";
$host = "https://gator4253.hostgator.com:2083";
$cpapi = new DCLMN_Cpanel_API($cpanel_user, $api_token, $host);

// $cpapi->load_precinct_forwarders();
// $cpapi->load_leadership_forwarders();

// $cpapi->delete_precinct_forwarders();
// $cpapi->delete_leadership_forwarders();

$forwarders = $cpapi->get_forwarders();
pobj($forwarders);




exit;


function napco_targetsmart_request($path, $params) {
  $api_key = '9ee6ecdc-8eea-c5bc-442d-d367f300b63d';

  $url = 'https://api.targetsmart.com/' . $path;

  $full_url = $url . '?' . http_build_query($params);

  $headers = [
    'x-api-key: ' . $api_key,
    // 'Content-Type: application/json'
  ];
  pobj($full_url);
  pobj($headers);

  $ch = curl_init();

  curl_setopt_array($ch, [
    CURLOPT_URL => $full_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $headers,
  ]);

  $response = curl_exec($ch);
  pobj($response);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

  // curl_close($ch);

  $data = json_decode($response, true);
  pobj($data);

  return $data;
}



function napco_get_precinct_voters($page = 1) {

  return napco_targetsmart_request('person/listbuilder', [
    'mode'=>'list',
      "where"=> "vb.voterbase_gender='Male' AND vb.vf_source_state = 'OH'",
  ]);
}


// Pull entire precinct
$page = 1;
$all_voters = [];

do {

  $response = napco_get_precinct_voters($page);
  $data = json_decode($response, true);

  if (empty($data['output'])) {
    break;
  }

  foreach ($data['output'] as $voter) {
    $all_voters[] = $voter;

    // store in DB instead of memory in real use
  }

  $page++;
} while (true);

echo 'Total voters pulled: ' . count($all_voters);




exit;






/**
 * 
 * Get all forewarders
 * Loop through all CP's 
 * Find forwarders that don't match current CP and delete
 * Add new one.
 * 
 * Loop through leadership?
 * 
 */

die(get_theme_file_path());
require_once dirname(__FILE__) . '/inc/classes/class.dclmn-cpanel-api.php';
$cpanel_user = 'xuvjwbte';
$api_token = "XECE9OGW23OCRBCFAWBCTMESJH7QG6QD";
$host = "https://gator4253.hostgator.com:2083";
$cpapi = new DCLMN_Cpanel_API($cpanel_user, $api_token, $host);

// $cpapi->load_precinct_forwarders();
// $cpapi->load_leadership_forwarders();

// $cpapi->delete_precinct_forwarders();
// $cpapi->delete_leadership_forwarders();

$forwarders = $cpapi->get_forwarders();
pobj($forwarders);

<?php
class DCLMN_Populator {

  function __construct() {
  }

  function run() {
    $this->populate_pa_districts();
    $this->populate_polling_places();
    $this->populate_precincts();
    $this->populate_dropboxes();
    $this->assign_cps();
    die('<hr>DONE?');
  }

  function get_dropboxes() {
    $url = 'https://services1.arcgis.com/kOChldNuKsox8qZD/arcgis/rest/services/Montgomery_County_Ballot_Dropbox_Locations/FeatureServer/0/query?f=json&where=(Municipality%20IN%20(%27Lower%20Merion%27))&outFields=*';
    if (!$dropboxes = get_transient('dropboxes')) {
      $file = file_get_contents($url);
      $file = json_decode($file);
      $dropboxes = wp_list_pluck($file->features, 'attributes');
      set_transient('dropboxes', $dropboxes, 60 * 60 * 24);
    }

    $dropboxes = array_map(function ($x) {
      $x = (array) $x;
      return array_change_key_case($x, CASE_LOWER);
    }, $dropboxes);


    return $dropboxes;
  }

  function get_pa_districts() {
    $url = 'https://services1.arcgis.com/kOChldNuKsox8qZD/arcgis/rest/services/Montgomery_County_PA_House_Districts_2022/FeatureServer/3/query?outFields=*&where=1%3D1&f=geojson';
    if (!$pa_districts = get_transient('pa_districts')) {
      $file = file_get_contents($url);
      $file = json_decode($file);
      $pa_districts = wp_list_pluck($file->features, 'properties');
      set_transient('pa_districts', $pa_districts, 60 * 60 * 24);
    }

    $pa_districts = array_map(function ($x) {
      $x = (array) $x;
      return array_change_key_case(array_map('trim', $x), CASE_LOWER);
    }, $pa_districts);

    return $pa_districts;
  }

  function get_precincts() {
    $url = 'https://gis.montcopa.org/arcgis/rest/services/Voters/Montgomery_County_Voting_Districts/FeatureServer/1/query?f=json&where=(Municipality%20IN%20(%27Lower%20Merion%27%2C%20%27Narberth%27))&outFields=*';
    if (!$precincts = get_transient('precincts')) {
      $file = file_get_contents($url);
      $file = json_decode($file);
      $precincts = wp_list_pluck($file->features, 'attributes');
      set_transient('precincts', $precincts, 60 * 60 * 24);
    }

    $precincts = array_map(function ($x) {
      $x = (array) $x;
      return array_change_key_case(array_map('trim', $x), CASE_LOWER);
    }, $precincts);

    return $precincts;
  }



  function get_polling_locations() {
    $url = 'https://gis.montcopa.org/arcgis/rest/services/Voters/Montgomery_County_Voting_Locations/FeatureServer/7/query?outFields=*&where=1%3D1&f=geojson';

    if (!$polling_locations = get_transient('polling_locations')) {
      $file = file_get_contents($url);
      $file = json_decode($file);
      $polling_locations = wp_list_pluck($file->features, 'properties');
      set_transient('polling_locations', $polling_locations, 60 * 60 * 24);
    }

    $polling_locations = array_map(function ($x) {
      $x = (array) $x;
      return array_change_key_case(array_map('trim', $x), CASE_LOWER);
    }, $polling_locations);

    $polling_locations = array_filter($polling_locations, function ($item) {
      return str_contains($item["precinct_name"], "Lower Merion") || str_contains($item["precinct_name"], "Narberth");
    });

    $polling_locations = array_values($polling_locations);

    return $polling_locations;
  }

  function get_polling_location_id($precinct_number) {
    if ($precinct_number) {
      // Try to find a polling_place post with the same precinct_number
      $polling_place = get_posts([
        'post_type'      => 'polling_place',
        'meta_key'       => 'precinct_number',
        'meta_value'     => $precinct_number,
        'posts_per_page' => 1,
        'fields'         => 'ids',
      ]);

      return $polling_place[0] ?? 0;
    }
  }

  function get_pa_district_id($district) {
    if ($district) {
      $title = 'PA ' . $district;
      $pa_district = get_page_by_title($title, OBJECT, 'pa_district');
      return $pa_district->ID ?? 0;
    }
  }

  function upsert_polling_location($data) {
    // Require the key field
    if (empty($data['precinct_number'])) {
      return new WP_Error('missing_precinct_number', 'Missing precinct_number field.');
    }

    $precinct_number = sanitize_text_field($data['precinct_number']);

    // Try to find an existing precinct with this number
    $existing = get_posts([
      'post_type'  => 'polling_place',
      'meta_key'   => 'precinct_number',
      'meta_value' => $precinct_number,
      'numberposts' => 1,
      'post_status' => 'any',
    ]);

    if (! empty($existing)) {
      $post_id = $existing[0]->ID;
    } else {
      $post_id = 0;
    }

    // Build post args
    $post_args = [
      'ID'           => $post_id,
      'post_type'    => 'polling_place',
      'post_status'  => 'publish',
      'post_title'   => sanitize_text_field($data['precinct_name'] . ' : ' . $data['site_name'] ?? $data['precinct_number']),
    ];

    // Insert or update post
    $post_id = wp_insert_post($post_args);

    if (is_wp_error($post_id)) {
      return $post_id;
    }

    foreach ($data as $k => $v) {
      update_post_meta($post_id, $k, $v);
    }

    return $post_id;
  }


  function upsert_precinct($data) {
    // Require the key field
    if (empty($data['precinct_number'])) {
      return new WP_Error('missing_precinct_number', 'Missing precinct_number field.');
    }

    $precinct_number = sanitize_text_field($data['precinct_number']);

    $precinct_name = $data['precinct_name'];
    $precinct_name = preg_replace('/\d+th State House/', '', $precinct_name);
    $precinct_name = preg_replace('/\d+th Congressional/', '', $precinct_name);

    // Try to find an existing precinct with this number
    $existing = get_posts([
      'post_type'  => 'precinct',
      'meta_key'   => 'precinct_number',
      'meta_value' => $precinct_number,
      'numberposts' => 1,
      'post_status' => 'any',
    ]);

    if (! empty($existing)) {
      $post_id = $existing[0]->ID;
    } else {
      $post_id = 0;
    }

    // Build post args
    $post_args = [
      'ID'           => $post_id,
      'post_type'    => 'precinct',
      'post_status'  => 'publish',
      'post_title'   => sanitize_text_field($precinct_name ?? $data['precinct_number']),
    ];

    // Insert or update post
    $post_id = wp_insert_post($post_args);

    if (is_wp_error($post_id)) {
      return $post_id;
    }

    foreach ($data as $k => $v) {
      update_post_meta($post_id, $k, $v);
    }

    // Set the relationship on the precinct post
    update_post_meta($post_id, 'polling_place_id', $this->get_polling_location_id($precinct_number));
    update_post_meta($post_id, 'pa_district_id', $this->get_pa_district_id($data['pa_house_2022']));

    return $post_id;
  }

  function getOrdinalSuffix($number) {
    if (!is_numeric($number)) {
      return ''; // Handle non-numeric input
    }
    $number = abs(intval($number)); // Work with positive integer
    if ($number % 100 >= 11 && $number % 100 <= 13) {
      return 'th';
    } else {
      switch ($number % 10) {
        case 1:
          return 'st';
        case 2:
          return 'nd';
        case 3:
          return 'rd';
        default:
          return 'th';
      }
    }
  }

  function upsert_pa_district($data) {
    $district = sanitize_text_field($data['district']);

    // Try to find an existing precinct with this number
    $existing = get_posts([
      'post_type'  => 'pa_district',
      'meta_key'   => 'district',
      'meta_value' => $district,
      'numberposts' => 1,
      'post_status' => 'any',
    ]);

    if (! empty($existing)) {
      $post_id = $existing[0]->ID;
    } else {
      $post_id = 0;
    }

    // Build post args
    $post_args = [
      'ID'           => $post_id,
      'post_type'    => 'pa_district',
      'post_status'  => 'publish',
      'post_title'   => sanitize_text_field('PA ' . $data['district'] . $this->getOrdinalSuffix($data['district'])),
    ];

    // Insert or update post
    $post_id = wp_insert_post($post_args);

    if (is_wp_error($post_id)) {
      return $post_id;
    }

    foreach ($data as $k => $v) {
      update_post_meta($post_id, $k, $v);
    }

    return $post_id;
  }

  function upsert_dropbox($data) {
    $objectid = sanitize_text_field($data['objectid']);

    // Try to find an existing precinct with this number
    $existing = get_posts([
      'post_type'  => 'dropbox',
      'meta_key'   => 'objectid',
      'meta_value' => $objectid,
      'numberposts' => 1,
      'post_status' => 'any',
    ]);

    if (! empty($existing)) {
      $post_id = $existing[0]->ID;
    } else {
      $post_id = 0;
    }

    // Build post args
    $post_args = [
      'ID'           => $post_id,
      'post_type'    => 'dropbox',
      'post_status'  => 'publish',
      'post_title'   => sanitize_text_field($data['name']),
    ];

    // Insert or update post
    $post_id = wp_insert_post($post_args);

    if (is_wp_error($post_id)) {
      return $post_id;
    }

    foreach ($data as $k => $v) {
      update_post_meta($post_id, $k, $v);
    }

    return $post_id;
  }


  function assign_cps() {
    // Get all committee_person posts
    $committee_people = get_posts([
      'post_type'      => 'committee_person',
      'posts_per_page' => -1,
      'post_status'    => 'any',
    ]);

    foreach ($committee_people as $person) {
      $title = $person->post_title;
      $precinct_name = '';

      // Match N-<number> pattern → Narberth <number>
      if (preg_match('/^N-(\d+)/', $title, $matches)) {
        $precinct_name = 'Narberth ' . $matches[1];
      }
      // Match <ward>-<precinct> pattern → Lower Merion <ward>-<precinct>
      elseif (preg_match('/^(\d+)-(\d+)/', $title, $matches)) {
        $precinct_name = 'Lower Merion ' . $matches[1] . '-' . $matches[2];
      }

      if ($precinct_name) {
        // Find the corresponding precinct post
        $precinct = get_page_by_title($precinct_name, OBJECT, 'precinct');

        if ($precinct) {
          // Build post args
          $post_title = $precinct_name . ' ' . str_replace($matches[0], '', $person->post_title);

          //set title?
          // $post_args = [
          //     'ID'           => $person->ID,
          //     'post_type'    => 'committee_person',
          //     'post_title'   => sanitize_text_field($precinct_name . ' ' . $person->post_title),
          // ];
          // $post_id = wp_update_post($post_args);

          //set cp's precinct
          update_post_meta($person->ID, 'precinct', $precinct->ID);

          // set precinct cp
          //preg_match('/#(\d)$/', $person->post_title, $matches);
          //update_post_meta($precinct->ID, 'cp_'. $matches[1] .'_id', $person->ID);
        } else {
          die("No precinct found for {$precinct_name}");
        }
      }
    }
  }


  function sort_posts_naturally($post_type) {
    $posts = get_posts([
      'post_type'      => $post_type,
      'posts_per_page' => -1,
      'post_status'    => 'any',
      'orderby'        => 'title',
      'order'          => 'ASC',
    ]);

    if (empty($posts)) return;

    // Sort titles naturally, case-insensitive
    usort($posts, function ($a, $b) {
      return strnatcasecmp($a->post_title, $b->post_title);
    });

    // Update menu_order sequentially
    $menu_order = 0;
    foreach ($posts as $post) {
      wp_update_post([
        'ID'          => $post->ID,
        'menu_order'  => $menu_order++,
      ]);
    }

    echo '<div style="padding:10px;background:#efe;border:1px solid #9c9;">Menu order updated for ' . count($posts) . ' posts.</div>';
  }

  function populate_polling_places() {
    foreach ($this->get_polling_locations() as $pl) {
      $this->upsert_polling_location($pl);
    }
    $this->sort_posts_naturally('polling_locatio');
  }


  function populate_precincts() {
    foreach ($this->get_precincts() as $p) {
      $this->upsert_precinct($p);
    }
    $this->sort_posts_naturally('precinct');
  }

  function populate_dropboxes() {
    foreach ($this->get_dropboxes() as $p) {
      $this->upsert_dropbox($p);
    }
    $this->sort_posts_naturally('dropbox');
  }

  function populate_pa_districts() {
    foreach ($this->get_pa_districts() as $p) {
      $this->upsert_pa_district($p);
    }
    $this->sort_posts_naturally('pa_district');
  }
}

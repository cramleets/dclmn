<?php

class DCLMN_ACF {
  function __construct() {
    if (function_exists('acf_add_local_field_group')) {
      $this->add_acf_fields_dropbox();
      $this->add_acf_fields_pa_district();
      $this->add_acf_fields_polling_place();
      $this->add_acf_fields_precinct();
    }
  }
  function add_acf_fields_precinct() {
    $fields = [
      ['key' => 'field_pa_district_id', 'label' => 'PA District', 'name' => 'pa_district_id', 'type' => 'post_object', 'post_type' => ['pa_district'], 'return_format' => 'id', 'ui' => 1],
      ['key' => 'field_polling_place_id', 'label' => 'Polling Place', 'name' => 'polling_place_id', 'type' => 'post_object', 'post_type' => ['polling_place'], 'return_format' => 'id', 'ui' => 1],
      //['key' => 'field_cp_1_id', 'label' => 'CP 1', 'name' => 'cp_1_id', 'type' => 'post_object', 'post_type' => ['committee_person'], 'return_format' => 'id', 'ui' => 1],
      //['key' => 'field_cp_2_id', 'label' => 'CP 2', 'name' => 'cp_2_id', 'type' => 'post_object', 'post_type' => ['committee_person'], 'return_format' => 'id', 'ui' => 1],
      ['key' => 'field_objectid', 'label' => 'Object ID', 'name' => 'objectid', 'type' => 'number'],
      ['key' => 'field_full_muni_name', 'label' => 'Full Municipality Name', 'name' => 'full_muni_name', 'type' => 'text'],
      ['key' => 'field_municipality', 'label' => 'Municipality', 'name' => 'municipality', 'type' => 'text'],
      ['key' => 'field_ward', 'label' => 'Ward', 'name' => 'ward', 'type' => 'text'],
      ['key' => 'field_districtid', 'label' => 'District ID', 'name' => 'districtid', 'type' => 'text'],
      ['key' => 'field_precinct_sort', 'label' => 'Precinct Sort', 'name' => 'precinct_sort', 'type' => 'text'],
      ['key' => 'field_precinct_name', 'label' => 'Precinct Name', 'name' => 'precinct_name', 'type' => 'text'],
      ['key' => 'field_precinct_number', 'label' => 'Precinct Number', 'name' => 'precinct_number', 'type' => 'text'],
      ['key' => 'field_magisterial_district', 'label' => 'Magisterial District', 'name' => 'magisterial_district', 'type' => 'text'],
      ['key' => 'field_registered_voters', 'label' => 'Registered Voters', 'name' => 'registered_voters', 'type' => 'number'],
      ['key' => 'field_registered_dem', 'label' => 'Registered Democrats', 'name' => 'registered_dem', 'type' => 'number'],
      ['key' => 'field_registered_rep', 'label' => 'Registered Republicans', 'name' => 'registered_rep', 'type' => 'number'],
      ['key' => 'field_registered_ind', 'label' => 'Registered Independents', 'name' => 'registered_ind', 'type' => 'number'],
      ['key' => 'field_registered_oth', 'label' => 'Registered Other', 'name' => 'registered_oth', 'type' => 'number'],
      ['key' => 'field_dem_rep', 'label' => 'Dem/Rep Lean', 'name' => 'dem_rep', 'type' => 'text'],
      ['key' => 'field_school_district', 'label' => 'School District', 'name' => 'school_district', 'type' => 'text'],
      ['key' => 'field_school_district_split', 'label' => 'School District Split', 'name' => 'school_district_split', 'type' => 'text'],
      ['key' => 'field_sure_system_name', 'label' => 'SURE System Name', 'name' => 'sure_system_name', 'type' => 'text'],
      ['key' => 'field_lrc_name', 'label' => 'LRC Name', 'name' => 'lrc_name', 'type' => 'text'],
      ['key' => 'field_total_population_2020', 'label' => 'Total Population 2020', 'name' => 'total_population_2020', 'type' => 'number'],
      ['key' => 'field_polling_location', 'label' => 'Polling Location', 'name' => 'polling_location', 'type' => 'text'],
      ['key' => 'field_address', 'label' => 'Address', 'name' => 'address', 'type' => 'text'],
      ['key' => 'field_city', 'label' => 'City', 'name' => 'city', 'type' => 'text'],
      ['key' => 'field_state', 'label' => 'State', 'name' => 'state', 'type' => 'text'],
      ['key' => 'field_zip', 'label' => 'ZIP', 'name' => 'zip', 'type' => 'text'],
      ['key' => 'field_sample_ballot_precinct', 'label' => 'Sample Ballot Precinct', 'name' => 'sample_ballot_precinct', 'type' => 'text'],
      ['key' => 'field_sample_ballot_muni', 'label' => 'Sample Ballot Municipality', 'name' => 'sample_ballot_muni', 'type' => 'text'],
      ['key' => 'field_cd_2022', 'label' => 'Congressional District 2022', 'name' => 'cd_2022', 'type' => 'text'],
      ['key' => 'field_pa_senate_2022', 'label' => 'PA Senate 2022', 'name' => 'pa_senate_2022', 'type' => 'text'],
      ['key' => 'field_pa_house_2022', 'label' => 'PA House 2022', 'name' => 'pa_house_2022', 'type' => 'text'],
      ['key' => 'field_shape_area', 'label' => 'Shape Area', 'name' => 'shape__area', 'type' => 'number'],
      ['key' => 'field_shape_length', 'label' => 'Shape Length', 'name' => 'shape__length', 'type' => 'number'],
    ];

    acf_add_local_field_group([
      'key' => 'group_precinct_data',
      'title' => 'Precinct Data',
      'fields' => $fields,
      'location' => [
        [
          ['param' => 'post_type', 'operator' => '==', 'value' => 'precinct'],
        ],
      ],
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'active' => true,
    ]);
  }


  function add_acf_fields_polling_place() {
    $group_data = [
      'location' => [
        [
          ['param' => 'post_type', 'operator' => '==', 'value' => 'polling_place'],
        ],
      ],
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'active' => true,
    ];
    
    $fields = [
      ['key' => 'field_objectid', 'label' => 'Object ID', 'name' => 'objectid', 'type' => 'number'],
      ['key' => 'field_precinct_name', 'label' => 'Precinct Name', 'name' => 'precinct_name', 'type' => 'text'],
      ['key' => 'field_site_name', 'label' => 'Site Name', 'name' => 'site_name', 'type' => 'text'],
      ['key' => 'field_address', 'label' => 'Address', 'name' => 'address', 'type' => 'text'],
      ['key' => 'field_city', 'label' => 'City', 'name' => 'city', 'type' => 'text'],
      ['key' => 'field_state', 'label' => 'State', 'name' => 'state', 'type' => 'text'],
      ['key' => 'field_zip', 'label' => 'ZIP', 'name' => 'zip', 'type' => 'text'],
      ['key' => 'field_precinct_number', 'label' => 'Precinct Number', 'name' => 'precinct_number', 'type' => 'text'],
      ['key' => 'field_precinct_sort', 'label' => 'Precinct Sort', 'name' => 'precinct_sort', 'type' => 'text'],
      ['key' => 'field_latitude', 'label' => 'Latitude', 'name' => 'latitude', 'type' => 'number', 'step' => 0.00000001],
      ['key' => 'field_longitude', 'label' => 'Longitude', 'name' => 'longitude', 'type' => 'number', 'step' => 0.00000001],
    ];

    $group_data['key'] = 'group_polling_place_api_data';
    $group_data['title'] = 'Polling Place API Data';
    $group_data['fields'] = $fields;
    acf_add_local_field_group($group_data);
    
    $fields = [
      ['key' => 'field_custom_label', 'label' => 'Custom Label', 'name' => 'custom_label', 'type' => 'text'],
      ['key' => 'field_map_url', 'label' => 'Map URL', 'name' => 'map_url', 'type' => 'url'],
    ];

    $group_data['key'] = 'group_polling_place_custom_data';
    $group_data['title'] = 'Polling Place Custom Data';
    $group_data['fields'] = $fields;
    acf_add_local_field_group($group_data);
  }

  function add_acf_fields_dropbox() {
    $fields = [
      ['key' => 'field_objectid', 'label' => 'Object ID', 'name' => 'objectid', 'type' => 'number'],
      ['key' => 'field_name', 'label' => 'Name', 'name' => 'name', 'type' => 'text'],
      ['key' => 'field_address', 'label' => 'Address', 'name' => 'address', 'type' => 'text'],
      ['key' => 'field_city', 'label' => 'City', 'name' => 'city', 'type' => 'text'],
      ['key' => 'field_state', 'label' => 'State', 'name' => 'state', 'type' => 'text'],
      ['key' => 'field_zip', 'label' => 'ZIP', 'name' => 'zip', 'type' => 'text'],
      ['key' => 'field_municipality', 'label' => 'Municipality', 'name' => 'municipality', 'type' => 'text'],
      ['key' => 'field_county', 'label' => 'County', 'name' => 'county', 'type' => 'text'],
      ['key' => 'field_latitude', 'label' => 'Latitude', 'name' => 'latitude', 'type' => 'number', 'step' => 0.00000001],
      ['key' => 'field_longitude', 'label' => 'Longitude', 'name' => 'longitude', 'type' => 'number', 'step' => 0.00000001],
      ['key' => 'field_weekday_hours', 'label' => 'Weekday Hours', 'name' => 'weekday_hours', 'type' => 'text'],
      ['key' => 'field_weekend_hours', 'label' => 'Weekend Hours', 'name' => 'weekend_hours', 'type' => 'text'],
      ['key' => 'field_election_day_hours', 'label' => 'Election Day Hours', 'name' => 'election_day_hours', 'type' => 'text'],
      ['key' => 'field_location_name', 'label' => 'Location Name', 'name' => 'location_name', 'type' => 'text'],
    ];

    acf_add_local_field_group([
      'key' => 'group_dropbox_data',
      'title' => 'Dropbox Data',
      'fields' => $fields,
      'location' => [
        [
          ['param' => 'post_type', 'operator' => '==', 'value' => 'dropbox'],
        ],
      ],
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'active' => true,
    ]);
  }


  function add_acf_fields_pa_district() {
    $fields = [
      ['key' => 'field_objectid', 'label' => 'Object ID', 'name' => 'objectid', 'type' => 'number'],
      ['key' => 'field_district', 'label' => 'District', 'name' => 'district', 'type' => 'text'],
      ['key' => 'field_last_name', 'label' => 'Last Name', 'name' => 'last_name', 'type' => 'text'],
      ['key' => 'field_first_name', 'label' => 'First Name', 'name' => 'first_name', 'type' => 'text'],
      ['key' => 'field_home_county', 'label' => 'Home County', 'name' => 'home_county', 'type' => 'text'],
      ['key' => 'field_party', 'label' => 'Party', 'name' => 'party', 'type' => 'text'],
      ['key' => 'field_population_2020', 'label' => 'Population 2020', 'name' => 'population_2020', 'type' => 'number'],
      ['key' => 'field_registered_voters', 'label' => 'Registered Voters', 'name' => 'registered_voters', 'type' => 'number'],
      ['key' => 'field_registered_dem', 'label' => 'Registered Democrats', 'name' => 'registered_dem', 'type' => 'number'],
      ['key' => 'field_registered_rep', 'label' => 'Registered Republicans', 'name' => 'registered_rep', 'type' => 'number'],
      ['key' => 'field_registered_ind', 'label' => 'Registered Independents', 'name' => 'registered_ind', 'type' => 'number'],
      ['key' => 'field_registered_oth', 'label' => 'Registered Other', 'name' => 'registered_oth', 'type' => 'number'],
      ['key' => 'field_total_vtd', 'label' => 'Total VTD', 'name' => 'total_vtd', 'type' => 'number'],
      ['key' => 'field_web_url', 'label' => 'Web URL', 'name' => 'web_url', 'type' => 'url'],
      ['key' => 'field_shape_area', 'label' => 'Shape Area', 'name' => 'shape__area', 'type' => 'number'],
      ['key' => 'field_shape_length', 'label' => 'Shape Length', 'name' => 'shape__length', 'type' => 'number'],
    ];

    acf_add_local_field_group([
      'key' => 'group_pa_district_data',
      'title' => 'PA District Data',
      'fields' => $fields,
      'location' => [
        [
          ['param' => 'post_type', 'operator' => '==', 'value' => 'pa_district'],
        ],
      ],
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'active' => true,
    ]);
  }
}

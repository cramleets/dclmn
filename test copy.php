<?php

//phpinfo(); exit;
require(dirname(__FILE__) . '/wp-blog-header.php');

exit;


$rows = array_map('str_getcsv', file('/Users/mps/Desktop/Committee.csv'));
//echo '<pre>'; print_r($rows);exit;

$header = array_shift($rows);
$file = array();
foreach ($rows as $row) {
    //print_r($header);exit;
    $file[] = array_combine($header, $row);
}

$headers = array_shift($file);

//print_r($file); exit;

foreach ($file as $i => $line) {
    //$line = array_combine($headers, $line);
    //print_r($line);
    // exit;

    $ward_title = array_values($line)[0];
    if ($existing_committee_person = get_page_by_path(sanitize_title($ward_title), OBJECT, 'committee_person')) {
        if ('publish' == $existing_committee_person->post_status) continue;
    }

    $args = [
        'post_type' => 'committee_person',
        'post_title' => $line['name'],
        'post_status' => 'publish',
    ];
    //print_r($args); exit;

    $post_id = wp_insert_post($args);

    $name = explode(' ', $line['name']);

    update_post_meta($post_id, 'public_email', $line['email']);
    update_post_meta($post_id, 'first_name', $name[0]);
    update_post_meta($post_id, 'last_name', $name[1]);

    if ($ward = get_page_by_path(sanitize_title($ward_title), OBJECT, 'ward')) {
        update_post_meta($post_id, 'ward', $ward->ID);
    }

    //wp_set_post_terms( int $post_id, string|array $terms = ”, string $taxonomy = ‘post_tag’, bool $append = false ): array|false|WP_Error
    //wp_set_post_terms($post_id, [16], 'jurisdiction');
}

die('done');

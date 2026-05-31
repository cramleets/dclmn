<?php

$labels = array(
    'name' => 'Poll Votes',
    'singular_name' => 'Poll Vote',
    'menu_name' => 'Poll Votes',
    'name_admin_bar' => 'Poll Vote',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Poll Vote',
    'new_item' => 'New Poll Vote',
    'edit_item' => 'Edit Poll Vote',
    'view_item' => 'View Poll Vote',
    'all_items' => 'All Poll Vote',
    'search_items' => 'Search Poll Votes',
    'parent_item_colon' => 'Parent Poll Votes:',
    'not_found' => 'No Poll Votes Found.',
    'not_found_in_trash' => 'None found in Trash.'
);

$args = array(
    'labels' => $labels,
    'description' => '',
    'public' => false,
    'publicly_queryable' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => false,
    'rewrite' => false,
    'capability_type' => 'post',
    'has_archive' => false,
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array(
        'title',
    )
);

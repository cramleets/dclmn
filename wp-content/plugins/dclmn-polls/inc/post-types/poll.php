<?php

$labels = array(
    'name' => 'Polls',
    'singular_name' => 'Poll',
    'menu_name' => 'Polls',
    'name_admin_bar' => 'Polls',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Poll',
    'new_item' => 'New Poll',
    'edit_item' => 'Edit Poll',
    'view_item' => 'View Poll',
    'all_items' => 'All Polls',
    'search_items' => 'Search Polls',
    'parent_item_colon' => 'Parent Poll:',
    'not_found' => 'No Poll Found.',
    'not_found_in_trash' => 'None found in Trash.'
);

$args = array(
    'labels' => $labels,
    'description' => '',
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => ['slug' => 'poll', 'with_front' => false],
    'capability_type' => 'post',
    'has_archive' => false,
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array(
        'title',
        'editor',
        'thumbnail',
    )
);

<?php

$labels = array(
    'name' => 'Poll Choices',
    'singular_name' => 'Poll Choice',
    'menu_name' => 'Poll Choices',
    'name_admin_bar' => 'Poll Choice',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Poll Choice',
    'new_item' => 'New Poll Choice',
    'edit_item' => 'Edit Poll Choice',
    'view_item' => 'View Poll Choice',
    'all_items' => 'All Poll Choice',
    'search_items' => 'Search Poll Choices',
    'parent_item_colon' => 'Parent Poll Choices:',
    'not_found' => 'No Poll Choices Found.',
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

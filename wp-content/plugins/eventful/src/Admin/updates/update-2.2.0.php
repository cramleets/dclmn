<?php

/**
 * Update version.
 */
update_option('eventful_version', '2.2.0');

/**
 * Convert old data keys to new ones.
 */
function eventful_convert_old_to_new_data_2_2_0()
{
    $args = array(
        'post_type'      => 'eventful',
        'post_status'    => 'publish',
        'posts_per_page' => -1
    );

    $posts = (new \WP_Query($args))->posts;

    foreach ($posts as $post) {
        $options = (array) get_post_meta($post->ID, 'eventful_view_options', true);

        $eventful_filter_options  = (array) ($options['eventful_filter_options'] ?? []);
        $filter_options_group     = (array) ($eventful_filter_options['filter_options_group'] ?? []);
        $eventful_advanced_filter = (array) ($options['eventful_advanced_filter'] ?? []);

        if (in_array('filter_option', $eventful_advanced_filter, true)) {
            foreach ($filter_options_group as $filter_item) {
                $filter_option            = isset($filter_item['filter_option']) ? $filter_item['filter_option'] : '';
                $eventful_event_type_time = isset($filter_item['eventful_event_type_time']) ? $filter_item['eventful_event_type_time'] : '';
                if ($filter_option === 'event_type_time') {
                    if ($eventful_event_type_time === 'future') {
                        $options['eventful_event_type'] = 'future';
                    } elseif ($eventful_event_type_time === 'past') {
                        $options['eventful_event_type'] = 'past';
                    } else {
                        $options['eventful_event_type'] = 'all';
                    }
                }
            }
        }

        update_post_meta($post->ID, 'eventful_view_options', $options);
        clean_post_cache($post->ID);
    }
}

/**
 * Run DB update only once after plugin upgrade
 */
function eventful_db_update_2_2_0()
{
    if (get_option('eventful_db_version') === '2.2.0') {
        return;
    }

    eventful_convert_old_to_new_data_2_2_0();

    update_option('eventful_db_version', '2.2.0');
}

add_action('admin_init', 'eventful_db_update_2_2_0');

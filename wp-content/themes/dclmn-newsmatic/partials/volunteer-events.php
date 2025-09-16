<?php

$args = array(
	'post_type'      => 'tribe_events',
	'posts_per_page' => 5,
	'tax_query'      => array(
		array(
			'taxonomy' => 'tribe_events_cat',
			'field'    => 'slug',
			'terms'    => 'volunteer',
		),
	),
	'orderby'        => 'event_date',
	'order'          => 'ASC',
);

// pobj(get_posts($args));

// tribe_get_events();

the_widget(
	'Tribe\Events\Views\V2\Widgets\Widget_List',
	array(
		'title'       => 'Upcoming Volunteer Events',
		'limit'       => 5,
		'event_tax'   => 'tribe_events_cat',
		'event_terms' => 'music', // category slug
	)
);


<?php

$taxonomy = 'election';
$out = '<div class="entry-content elections">';

$parents = get_terms([
	'taxonomy'   => $taxonomy,
	'parent'     => 0,
	'hide_empty' => false,
	'orderby'    => 'name',
	'order'      => 'ASC'
]);

foreach ($parents as $parent) {
	$out .= '<h2>' . esc_html($parent->name) . '</h2>';

	$children = get_terms([
		'taxonomy'   => $taxonomy,
		'parent'     => $parent->term_id,
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC'
	]);

	foreach ($children as $child) {
		$out .= '<h3>' . esc_html($child->name) . '</h3><ul>';

		$posts = get_posts([
			'post_type'   => 'candidate',
			'numberposts' => -1,
			'tax_query'   => [[
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => $child->term_id,
			]],
		]);

		// Sort by last_name meta
		usort($posts, function($a, $b) {
			$lastA = get_post_meta($a->ID, 'last_name', true);
			$lastB = get_post_meta($b->ID, 'last_name', true);
			$lastA = $lastA ?: $a->post_title;
			$lastB = $lastB ?: $b->post_title;
			return strcasecmp($lastA, $lastB);
		});

		foreach ($posts as $post) {
			$link = get_post_meta($post->ID, 'website_campaign', true);
			$name = esc_html($post->post_title);
			if ($link) {
				$out .= '<li><a href="' . esc_url($link) . '" target="_blank" rel="noopener">' . $name . '</a></li>';
			} else {
				$out .= '<li>' . $name . '</li>';
			}
		}

		$out .= '</ul>';
	}
}

$out .= '</div>';

echo $out;

<?php

use Newsmatic\CustomizerDefault as ND;

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
	$out .= '<div class="election-group">';
	$out .= '<h2 class="election-group-header">' . esc_html($parent->name) . '</h2>';

	$children = get_terms([
		'taxonomy'   => $taxonomy,
		'parent'     => $parent->term_id,
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC'
	]);

	foreach ($children as $child) {

		$posts = dclmn_get_posts([
			'post_type'   => 'candidate',
			'numberposts' => -1,
			'tax_query'   => [[
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => $child->term_id,
			]],
		]);

		// Sort by last_name meta
		usort($posts, function ($a, $b) {
			$lastA = get_post_meta($a->ID, 'last_name', true);
			$lastB = get_post_meta($b->ID, 'last_name', true);
			$lastA = $lastA ?: $a->post_title;
			$lastB = $lastB ?: $b->post_title;
			return strcasecmp($lastA, $lastB);
		});

		$num_candidates = count($posts);

		$out .= '<div class="election '. $child->slug .'">';
		$out .= '<h3 class="election-header">' . esc_html($child->name) . '</h3>';

		if ($num_candidates > 1) {
			$out .= ($text =  get_field('text', $child)) ? $text : '<br>';
		} else {
			$out .= '<br>';
		}

		$out .= '<ul class="flex">';
		foreach ($posts as $post) {
			$link = get_post_meta($post->ID, 'website_campaign', true);
			$out .= '<li>';

			$out .= (!empty($link)) ? '<a href="' . esc_url($link) . '" target="_blank" rel="noopener">' : '';

			if (has_post_thumbnail($post)):
				$src = dclmn_thumb(get_the_post_thumbnail_url($post, 'medium'), ['width' => 200]);
				$out .= '<img style="width: 200px;" src="' . esc_url($src) . '" alt="' . the_title_attribute(['post' => $post, 'echo' => false]) . '" />';
				$out .= '<br>';
			endif;

			$out .= (empty($post->title)) ? '' : $post->title .' ';
			$out .= $post->first_name .' '. $post->last_name;

			$out .= (!empty($link)) ? '</a>' : '';

			$out .= '</li>';
		}

		$out .= '</ul>';

		if ($num_candidates == 1) {
			$out .= ($text =  get_field('text', $child)) ? $text : '<br>';
		}

		$out .= '</div>';
	}
	$out .= '</div>';
}

$out .= '</div>';

echo $out;

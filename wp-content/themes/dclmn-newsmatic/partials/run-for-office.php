<?php

$post_type = 'run_for_office';

$posts = get_posts( array(
	'post_type' => $post_type,
	'post_status' => 'publish',
	'posts_per_page' => -1,
	//'orderby' => 'menu_order',
	//'order' => 'ASC',
) );

$children = array();

foreach ( $posts as $post ) {
	$children[ $post->post_parent ][] = $post;
}

function dclmn_build_post_tree( $parent_id, $children ) {
	$tree = array();

	foreach ( $children[ $parent_id ] ?? array() as $post ) {
		$item = array(
			'post' => $post,
			'children' => dclmn_build_post_tree( $post->ID, $children ),
		);

		$tree[] = $item;
	}

	return $tree;
}

$tree = dclmn_build_post_tree( 0, $children );

$form_link = '';
$form_link .= '<div style="background-color: #efefef; border: 1px solid #ccc; padding: 1em;`">';
$form_link .= '<h3 style="margin-top: 0;">Interested in running for office? We can help! Please <a href="'. home_url('interested-in-running') .'">fill out this form</a> to let us know.</h3>';
$form_link .= '<h3 style="margin-bottom: 0;">Find out more about <a href="'. home_url('dclmn-endorsement-process') .'">the DCLMN endorsement process</a>.</h3>';
$form_link .= '</div>';

$out = '';

$out .= $form_link .'<br>';
$out .= '<div id="run-for-office">';
foreach($tree as $muncipality_post) {
	$out .= '<h1 class="toggle open" data-target="'. $muncipality_post['post']->post_name .'"><b>'. $muncipality_post['post']->post_title .'</b></h1>';
	$out .= '<div id="'. $muncipality_post['post']->post_name .'" class="rfo-section">';
	foreach($muncipality_post['children'] as $office) {
		$url = get_post_meta($office['post']->ID, 'url', true);
		$out .= '<h2 class="toggle" data-target="'. $office['post']->post_name .'"><b>'. $office['post']->post_title .'</b></h2>';
		$out .= '<div id="'. $office['post']->post_name .'" class="rfo-subsection">';
		$out .= $office['post']->post_content;
		if (!empty($url)) $out .= '<p><a href="'. $url .'" target="_blank" style="font-size: 1.25em; font-weight: bold;">Learn More</a></p>';
		$out .= '</div>';
	}
	$out .= '</div>';
	//$out .= '<br>';
}
$out .= '</div>';
$out .= '<br>'. $form_link;

echo $out;

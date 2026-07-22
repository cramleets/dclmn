<?php

$events_args = [
  // 'header' => 'Meetings',
  'category' => 'canvassing',
  'posts_per_page' => 10,
  'show_title' => false,
  'show_date' => false,
  'show_more' => false,
];

$events = dclmn_homepage_events($events_args);

$out = '';
if (!empty($events)) {
	$out .= '<div class="newsmatic-container">';
	$out .= '<div class="canvassing-dates">';
	$out .= '<h2>Canvassing Dates</h2>';
	$out .= '<div class="canvassing-slider-intro">We’re hitting the doors to make sure every voter makes their voice heard. Whether you’re a first-time volunteer or a seasoned canvasser, we’ll get you trained, paired up, and ready to go. You’ll hear a quick kickoff from the organizing team, grab your turf, and head out to connect directly with voters in your community.</div>';
	$out .= '<div class="canvassing-slider dclmn-slick">';
	$out .= $events;
	$out .= '</div>';
	$out .= '</div>';
	$out .= '</div>';
}

echo $out;

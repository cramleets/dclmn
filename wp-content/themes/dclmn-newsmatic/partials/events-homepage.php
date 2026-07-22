
<?php

$volunteer_args = [
  'posts_per_page' => 5,
  'header' => 'Volunteer Events',
  'category' => 'volunteer',
];

$meetings_args = [
  'posts_per_page' => 3,
  'header' => 'Meetings',
  'category' => 'meetings',
];

$out = '';
$out .= '<div class="flex homepage-events">';
$out .= '<div>' . dclmn_homepage_events($volunteer_args) . '</div>';
$out .= '<div>' . dclmn_homepage_events($meetings_args) . '</div>';
$out .= '</div>';

echo $out;
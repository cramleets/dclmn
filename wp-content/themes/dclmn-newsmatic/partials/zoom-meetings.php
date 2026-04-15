<?php

$zoom = new DCLMN_Zoom_API();
$meetings = $zoom->get_meetings();
$webinars = $zoom->get_webinars();

$out = '';
$out .= '<h2>Upcoming Zoom Meetings</h2>';
$out .= '<ul>';
foreach ($meetings as $meeting) {
  $dt = new DateTime($meeting['start_time']);
  $dt->setTimezone(new DateTimeZone($meeting['timezone']));

  $meeting_date = $dt->format('F j, Y');
  $meeting_time = $dt->format('g:i a');

  $out .= '<li>';
  $out .= '<a href="' .  $meeting['join_url'] . '" target="_blank">';
  $out .= '<strong>'. $meeting['topic'] .'</strong>';
  $out .= '</a>';
  $out .= '<ul>';
  $out .= '<li><strong>Date</strong> ' . $meeting_date . ' at '. $meeting_time .'</li>';
  $out .= '<li><strong>Duration</strong> ' . $meeting['duration'] . ' minutes</li>';
  $out .= '<li><strong>Meeting ID</strong> ' . $meeting['id'] . '</li>';
  $out .= '</ul>';
  $out .= '</li>';
}
$out .= '</ul>';

echo $out;

<?php

$zoom = new DCLMN_Zoom_API();
$meetings = $zoom->get_meetings();
$webinars = $zoom->get_webinars();

$out = '';
$out .= '<div class="zoom-meetings dclmn-events">';
foreach ($meetings as $meeting) {
  if (!dclmn_auth('exec') && stristr($meeting['topic'], 'exec')) continue;
  $dt = new DateTime($meeting['start_time']);
  $dt->setTimezone(new DateTimeZone($meeting['timezone']));

  $meeting_date = $dt->format('l, F j, Y');
  $meeting_time = $dt->format('g:i a');

  $event_week_day         = $dt->format('l');
  $event_week_day_short   = $dt->format('D');
  $event_week_day_shorter = substr($event_week_day_short, 0, 2);

  $event_day_num          = $dt->format('j');
  $event_month            = $dt->format('F');
  $event_month_short      = $dt->format('M');
  $event_date_attr        = $dt->format('Y-m-d');
  $event_time_short       = $dt->format('g:i a');;


  $out .= '<p class="dclmn-event">';
  $out .= '<span class="dclmn-event-flex">';
  $out .= '<span>'; // date box flex item
  $out .= '<span class="date-box">';
  $out .= '<span class="date-box-dow">' . $event_month_short . '</span>';
  $out .= '<span class="date-box-date">' . $event_day_num . '</span>';
  $out .= '<span class="date-box-time">' . $event_time_short . '</span>';
  $out .= '</span>'; // /date box
  $out .= '</span>'; // /date box flex item

  $out .= '<span>'; // date links flex item

  $out .= '<a href="' .  $meeting['join_url'] . '" target="_blank">';
  $out .= '<strong>' . $meeting['topic'] . '</strong>';
  $out .= '</a>';
  $out .= '<br>' . $meeting_date . ' at ' . $meeting_time;
  //$out .= '<br>&bull; <strong>Duration</strong> ' . $meeting['duration'] . ' minutes';
  // $out .= '<br><strong>Meeting ID</strong> ' . $meeting['id'];
  $out .= '<br><a href="' .  $meeting['join_url'] . '" target="_blank"><strong>Click Here to Join</strong></a>';
  $out .= '</span>';
  $out .= '</span>'; // /date links flex item
  $out .= '</p>';
}
$out .= '</div>';

if (!empty($meetings)) {
  $out = '<h3>Upcoming Zoom Meetings</h3>' . $out . '';
}

echo $out;

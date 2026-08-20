<?php

global $dclmn;
$drop_boxes = $dclmn->get_drop_boxes();
$out = '';
$out .= '<p>Take 30 seconds to watch actor Leo DeCaprio learn from Montco County Commissioner Neil Makhija that <a href="https://www.facebook.com/reel/4344921609107155" target="_blank">voting by ballot drop box is secure</a>.</p>';
$out .= '<p class="drop-box-hours">';
$out .= 'Two official drop boxes are located in Lower Merion Township.<br>Open 24 hours a day until 8pm on Election Day.';
// $out .= '<span>Weekdays</span> <strong>' . $drop_box->weekday_hours . '</strong><br>';
// $out .= '<span>Weekends</span>  <strong>' . $drop_box->weekend_hours . '</strong><br>';
// $out .= '<span>Election Day</span> <strong>' . $drop_box->election_day_hours . '</strong><br>';
$out .= '</p>';
$out .= '<div class="drop-boxes">';
foreach ($drop_boxes as $drop_box) {
  $map_url = $dclmn->map_url($drop_box);
  $out .= '<div class="drop-box">';
  $out .= '<h3>' . $drop_box->name . '</h3>';
  if (!empty($drop_box->notes)) $out .= '<p style="font-style: italic; font-size: .95em;">'. $drop_box->notes .'</p>';
  $out .= '<p>';
  $out .= '<a href="' . $map_url . '">';
  $out .= $drop_box->address . '<br>';
  $out .= $drop_box->city . ', ' . $drop_box->state . ' ' . $drop_box->zip;
  $out .= '</a>';
  $out .= '</p>';
  $out .= '</div>';
}
$out .= '</div>';

$out .= '<div style="font-weight: bold;">Montgomery County has 18 official ballot drop boxes where any voter registered in the county may return their ballot. To see where all are located, go to <a href="https://www.montgomerycountypa.gov/departments/voter-services/vote-mail/returning-your-mail-ballot" target="_blank">this page at Montco Voter Services</a>.</div>';

echo $out;

<?php

global $dclmn;
$drop_boxes = $dclmn->get_drop_boxes();
$out = '';
$out .= '<div class="drop-boxes">';
foreach ($drop_boxes as $drop_box) {
  $map_url = $dclmn->map_url($drop_box);
  $out .= '<div class="drop-box">';
  $out .= '<h3>' . $drop_box->name . '</h3>';
  $out .= '<p>';
  $out .= '<a href="' . $map_url . '">';
  $out .= $drop_box->address . '<br>';
  $out .= $drop_box->city . ', ' . $drop_box->state . ' ' . $drop_box->zip;
  $out .= '</a>';
  $out .= '</p>';
  $out .= '<p>';
  $out .= '<span>Weekdays</span> <strong>' . $drop_box->weekday_hours . '</strong><br>';
  $out .= '<span>Weekends</span>  <strong>' . $drop_box->weekend_hours . '</strong><br>';
  $out .= '<span>Election Day</span> <strong>' . $drop_box->election_day_hours . '</strong><br>';
  $out .= '</p>';
  $out .= '</div>';
}
$out .= '</div>';
echo $out;

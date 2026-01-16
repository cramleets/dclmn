<?php

use Tribe__Date_Utils as Dates;

// Get upcoming events
$events = dclmn_get_events([
  'posts_per_page' => -1,
  // 'ends_after' => 'now',
  'tax_query' => array(
    array(
      'taxonomy' => 'tribe_events_cat',
      'field'    => 'slug',
      'terms'    => array('election-dates'),
      'operator' => 'IN',
    ),
  )
]);

$out = '';
$out .= '<div class="dclmn-events election-dates">';
$out .= '<h2>Election Dates and Deadlines</h2>';
$out .= '<ul>';
foreach ($events as $post) {
  $display_date = empty($is_past) && ! empty($request_date)
    ? max($post->dates->start_display, $request_date)
    : $post->dates->start_display;

  $event_week_day  = $display_date->format_i18n('l');
  $event_week_day_short  = $display_date->format_i18n('D');
  $event_week_day_shorter = substr($event_week_day_short, 0, 2);

  $event_day_num   = $display_date->format_i18n('j');
  $event_month   = $display_date->format_i18n('F');
  $event_month_short   = $display_date->format_i18n('M');
  $event_date_attr = $display_date->format(Dates::DBDATEFORMAT);

  $out .= '<li><a href="' . $post->permalink->__toString() . '"><span class="month">' . $event_month .'</span> <span class="dom">'. $event_day_num .'</span><br>'. $post->post_title . '</a></li>';
}
$out .= '</ul>';
$out .= '</div>';

echo $out;
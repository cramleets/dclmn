<?php

$out = '';
$channel_id = 'UCaQmivO7R6UzHnj_NmEI10g';
$feed_url   = "https://www.youtube.com/feeds/videos.xml?channel_id={$channel_id}";
$rss = fetch_feed($feed_url);

if (! is_wp_error($rss)) {
  $maxitems = $rss->get_item_quantity(25);
  $rss_items = $rss->get_items(0, $maxitems);
  if ($maxitems > 0) {
    $out .= '<div class="yt-feed flex">';
    foreach ($rss_items as $item) {
      $link  = $item->get_link();
      $title = $item->get_title();
      $date = $item->get_date('F Y');

      // Extract video ID from URL
      parse_str(parse_url($link, PHP_URL_QUERY), $params);
      $video_id = isset($params['v']) ? $params['v'] : '';

      if ($video_id) {
        $thumb = "https://i.ytimg.com/vi/{$video_id}/hqdefault.jpg";
        $src = dclmn_thumb($thumb, ['width' => 400, 'height' => 225]);

        $out .= '<div class="yt-item" data-video="' . esc_attr($video_id) . '">';
        $out .= '<a class="yt-item" href="' . esc_url($link) . '" target="_blank" rel="noopener">';
        $out .= '<img src="' . $src . '" alt="' . esc_attr($title) . '">';
        $out .= '<br><strong>' . $title . '</strong>';
        $out .= '</a>';
        $out .= '<br>' . $date;
        $out .= '</div>';
      }
    }
    $out .= '</div>';
  }
}

echo $out;

<?php

$channel_id = 'UCaQmivO7R6UzHnj_NmEI10g';
$all = 'https://www.youtube.com/feeds/videos.xml?channel_id=' . $channel_id;
$videos = 'https://www.youtube.com/feeds/videos.xml?playlist_id=UULFaQmivO7R6UzHnj_NmEI10g';
$shorts = 'https://www.youtube.com/feeds/videos.xml?playlist_id=UUSHaQmivO7R6UzHnj_NmEI10g';
$live = 'https://www.youtube.com/feeds/videos.xml?playlist_id=UULVaQmivO7R6UzHnj_NmEI10g';

function output_youtube_fe1ed($feed_url) {
  $out = '';
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

  return $out;
}

function output_youtube_feed($feed_url) {
  $cache_key = 'youtube_feed_' . md5($feed_url);
  $refresh_key = $cache_key . '_refresh';

  $cached = get_transient($cache_key);

  // Return cached content if refresh isn't due.
  if (false !== $cached && false !== get_transient($refresh_key)) {
    return $cached;
  }

  $rss = fetch_feed($feed_url);

  // If RSS fails, retain and return the last successful feed.
  if (is_wp_error($rss)) {
    return false !== $cached ? $cached : '';
  }

  $out = '';
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
        $out .= '<br><strong>' . esc_html($title) . '</strong>';
        $out .= '</a>';
        $out .= '<br>' . esc_html($date);
        $out .= '</div>';
      }
    }

    $out .= '</div>';
  }

  // Save successful response indefinitely.
  set_transient($cache_key, $out);

  // Don't attempt another refresh for 1 hour.
  set_transient($refresh_key, 1, HOUR_IN_SECONDS);

  return $out;
}

$out = '';
$out .= '<h2>Videos</h2>';
$out .= output_youtube_feed($videos);
$out .= '<hr>';
$out .= '<h2>Live Streams</h2>';
$out .= output_youtube_feed($live);
echo $out;

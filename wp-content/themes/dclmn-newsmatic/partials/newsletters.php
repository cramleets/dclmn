<?php
$items = dclmn_get_newsletters_from_mailchimp();

$out = '';
$out .= '<div class="newsletters-archive">';
foreach ($items as $item) {
  $out .= '<div><small>' . $item['date'] . '</small><br><a href="' . $item['permalink'] . '" target="_blank"><strong>' . $item['title'] . '</strong></a></div>';
}
$out .= '</div>';

echo $out;

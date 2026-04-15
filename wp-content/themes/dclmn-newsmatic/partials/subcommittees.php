<?php
global $dclmn;

$types = ['chair_person', 'cp'];

$args = [
  'post_type' => 'subcommittee',
  'orderby' => 'menu_order',
  'posts_per_page' => -1,
];
$subcommittees = dclmn_get_posts($args);

$out = '';
$out .= '<div class="subcommittees">';
foreach ($subcommittees as $subcommittee) {
  $out .= '<div class="subcommittee">';

  $out .= '<h3>' . $subcommittee->post_title . '</h3>';

  foreach ($types as $type) {
    for ($i = 1; $i <= 5; $i++) {
      $person = dclmn_get_post(get_field("{$type}_{$i}", $subcommittee->ID)[0]->ID);

      if (!empty($person)) {
        $out .= '<div>';
        $out .= '<strong>' . $person->first_name . ' ' . $person->last_name . '</strong>';
        if ($person->email) $out .= ', <a href="mailto:' . $person->email . '" target="_blank">' . $person->email . '</a>';
        if ($person->phone) $out .= ', ' . $dclmn->get_phone_link($person->phone);
        $out .= '</div>';
      }
    }
  }

  $out .= '<div>' . $subcommittee->post_content . '</div>';
  $out .= '</div>' . PHP_EOL;
}
$out .= '</div>';

echo $out;

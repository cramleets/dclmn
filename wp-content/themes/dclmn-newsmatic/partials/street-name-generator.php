<?php
require_once get_stylesheet_directory() . '/inc/classes/class.dclmn-street-name-generator.php';
$name = (new DCLMN_LM_Street_Name_Generator())->build_street_name();

$out = '';
$out .= '<div class="street-name-generator">';
$out .= '<h3>Your Street Name Is</h3>';
$out .= '<h2 id="street-name-generator-content">'. $name .'</h2>';
$out .= '<a href="#" class="button street-name-refresh">Click For Another Street Name</a>';
$out .= '</div>';

echo $out;
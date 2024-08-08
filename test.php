<?php

//phpinfo(); exit;
require(dirname(__FILE__) . '/wp-blog-header.php');

// $committee_people_table = $dclmn->get_committee_people_table();
// echo $committee_people_table;

$elected_officials_table = $dclmn->get_elected_officials_table();
echo $elected_officials_table;


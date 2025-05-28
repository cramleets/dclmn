<?php

//phpinfo(); exit;
require(dirname(__FILE__) . '/wp-blog-header.php');

// $committee_people_table = $dclmn->get_committee_people_table();
// echo $committee_people_table;


$jurisdictions = [
    'State Executive Office',
    'PA Senate',
    'PA State House',
    'US Senate',
    'US House of Representatives',
    'County Commissioners',
    'County Row Officers',
    'Lower Merion Township',
    'Narberth Borough',
    'Lower Merion School Board',
];

$args = [
    // 'search' => 'Lower Merion School Board',
    // 'search' => 'Narberth Borough',
    // 'search' => 'US House of Representatives',
];

$elected_officials_table = $dclmn->get_elected_officials_table($args);
echo $elected_officials_table;

exit;


$headers = [
    'email',
    'list',
    'region',
    'name',
    'precinct_raw',
    'phone',
];

$file = file_get_contents('/Users/mps/Desktop/cop-1.txt');
$file = explode("\n", trim($file));
$file = array_map(function ($x) {
    $x = explode("\t", $x);
    return $x;
}, $file);

$out = '';
$out .= '<table cellpadding="5" cellspacing="0" border="1" style="font-family: tahoma; font-size: 12px;">';

$out .= '<tr style="background-color: #000; color: #fff; font-weight: bold;">';

$out .= '<td>ward</td>';
$out .= '<td>district</td>';
$out .= '<td>name</td>';
$out .= '<td>email</td>';
$out .= '<td>phone</td>';

// $out .= '<td>list</td>';
// $out .= '<td>region</td>';
// $out .= '<td>precinct_raw</td>';
// $out .= '<td>$municipality</td>';
// $out .= '<td>distict raw</td>';
// $out .= '<td>distict</td>';
// $out .= '<td>ward</td>';
// $out .= '<td>precinct</td>';
// $out .= '<td>cp_number</td>';
$out .= '</tr>';




$districts = [
    148 => [
        'N',
        1,
        2,
        3,
        5,
        7,
        9,
        12,
        13,
        14
    ],
    149 => [
        4,
        5,
        6,
        8,
        10,
        11,
    ],
];


foreach($file as $line) {

    $email = $line[0];
    $list = $line[1];
    $region = $line[2];
    $name = $line[3];
    $precinct_raw = $line[4];
    $phone = $line[5];

    preg_match('/^(\d{2})(\d{2})(\d{2})(\d)/', $precinct_raw, $matches);


    $raw = $matches[0];
    $municipality = $matches[1];
    $ward = $matches[2];
    $precinct = $matches[3];
    $cp_number = $matches[4];

    if ($ward == 0 && $precinct) $ward = 'N';
    // $distict = (!$distict_raw) ? 148:149;



    $final_ward = ltrim($ward,0);

    if (in_array($final_ward, $districts[148])) {
        $district_final = 148;
    } else {
        $district_final = 149;
    }



    $out .= '<tr>';
    $out .= '<td align="right">'. $final_ward .'-'. intval($precinct) .'</td>';
    $out .= '<td align="right">'. $district_final .'</td>';
    $out .= '<td>'. $name .'</td>';
    $out .= '<td>'. $email .'</td>';
    $out .= '<td>'. $phone .'</td>';


    // $out .= '<td>'. $list .'</td>';
    // $out .= '<td>'. $region .'</td>';
    // $out .= '<td>'. $precinct_raw .'</td>';
    // $out .= '<td align="right">'. $municipality .'</td>';
    // $out .= '<td align="right">'. $distict_raw .'</td>';
    // $out .= '<td align="right">'. $distict .'</td>';
    // $out .= '<td align="right">'. $ward .'</td>';
    // $out .= '<td align="right">'. $precinct .'</td>';
    // $out .= '<td align="right">'. $cp_number .'</td>';
    $out .= '</tr>';
}
$out .= '</table>';

die($out);


pobj($file);

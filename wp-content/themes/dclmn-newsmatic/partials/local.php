<?php

global $dclmn;

$elected_officials_table = $dclmn->get_elected_officials_table(['search' => 'Lower Merion Township']);
echo $elected_officials_table;

$elected_officials_table = $dclmn->get_elected_officials_table(['search' => 'Narberth Borough']);
echo $elected_officials_table;

$elected_officials_table = $dclmn->get_elected_officials_table(['search' => 'Lower Merion School Board']);
echo $elected_officials_table;
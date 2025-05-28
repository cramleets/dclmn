<?php

global $dclmn;

$elected_officials_table = $dclmn->get_elected_officials_table(['search' => 'State Executive Office']);
echo $elected_officials_table;

$elected_officials_table = $dclmn->get_elected_officials_table(['search' => 'US Senate']);
echo $elected_officials_table;

$elected_officials_table = $dclmn->get_elected_officials_table(['search' => 'US House of Representatives']);
echo $elected_officials_table;

$elected_officials_table = $dclmn->get_elected_officials_table(['search' => 'PA Senate']);
echo $elected_officials_table;

$elected_officials_table = $dclmn->get_elected_officials_table(['search' => 'PA State House']);
echo $elected_officials_table;

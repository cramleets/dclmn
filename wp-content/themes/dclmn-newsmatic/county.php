<?php

global $dclmn;

$elected_officials_table = $dclmn->get_elected_officials_table(['search' => 'County Commissioners']);
echo $elected_officials_table;

$elected_officials_table = $dclmn->get_elected_officials_table(['search' => 'County Row Officers']);
echo $elected_officials_table;
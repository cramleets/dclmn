<?php

use Newsmatic\CustomizerDefault as ND;


// ---- Usage ----
$elections = dclmn_get_elections_data();
echo dclmn_render_elections_nav($elections);
echo dclmn_render_elections_output($elections);

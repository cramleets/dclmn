<?php
global $dclmn;
$dclmn_user = dclmn_get_user();
?>
<?php if (is_object($dclmn_user) && dclmn_user_is_exec()): ?>
  <?php get_template_part('partials/cp-nav'); ?>
  FORM GOES HERE
<?php endif; ?>
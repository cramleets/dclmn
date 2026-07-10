<?php
global $dclmn;
$dclmn_user = dclmn_get_user();
?>
<?php if (is_object($dclmn_user) && dclmn_user_is_exec()): ?>
  <?php get_template_part('partials/cp-nav'); ?>
  <?php echo do_shortcode('[formidable id=5]'); ?>
<?php endif; ?>
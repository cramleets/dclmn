<div class="button-group">
  <a class="button" href="https://www.pavoterservices.pa.gov/Pages/PollingPlaceInfo.aspx" target="_blank">Find My Precinct</a>
  <a class="button" href="<?php echo home_url('committee-person-description/') ?>">Committee Person Description</a>
  <a href="<?php echo home_url('map/') ?>" class="button">Map</a>
  <?php if (current_user_can('edit_others_posts')): ?>
    <a href="<?php echo admin_url('admin-ajax.php?action=export_cps') ?>" class="button" target="_blank">Export</a>
  <?php endif; ?>
</div>
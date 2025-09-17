<div class="button-group">
  <a class="button" href="https://www.pavoterservices.pa.gov/Pages/PollingPlaceInfo.aspx" target="_blank">Find My Precinct</a>
  <a class="button" href="<?php echo home_url('committee-person-description/') ?>">Committee Person Description</a>
  <a href="<?php echo home_url('map/') ?>" class="button">Map</a>
</div>
<?php if (current_user_can('edit_others_posts')): ?>
  <?php
  global $dclmn;
  $cp_emails = [];
  $cps = $dclmn->get_committee_people_table(true);
  array_shift($cps);
  $cp_emails = array_unique(array_filter(array_column($cps, 5)));
  asort($cp_emails);
  ?>
  <br>
  <div class="button-group">
    <a href="mailto:?bcc=<?php echo implode(',', $cp_emails) ?>" class="button" target="_blank">Email</a>
    <a href="<?php echo admin_url('admin-ajax.php?action=export_cps') ?>" class="button" target="_blank">Export</a>
  </div>
<?php endif; ?>
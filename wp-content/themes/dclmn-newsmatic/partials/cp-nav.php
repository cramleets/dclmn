<div class="cp-dashboard-nav">
  <a href="<?php echo home_url('cp/') ?>" class="button">Dashboard</a>
  <a href="<?php echo home_url('cp/cps/') ?>" class="button">CPs</a>
  <a href="<?php echo home_url('cp/leadership/') ?>" class="button">Leadership</a>
  <?php //if (dclmn_user_is_exec()): ?>
  <?php if (current_user_can('update_core')): ?>
    <a href="<?php echo home_url('cp/dclmn-contacts/') ?>" class="button">Contacts</a>
    <a href="<?php echo admin_url('/tools.php?page=dclmn-tools') ?>" class="button" target="_blank">Tools</a>
    <a href="<?php echo home_url('cp/precinct-voters/') ?>" class="button">My Voters</a>
  <?php endif; ?>
  <a href="<?php echo home_url('cp/?action=cp-logout&cb=' . uniqid()) ?>" class="button" onclick="return confirm('Are you sure?');">Log Out</a>
</div>
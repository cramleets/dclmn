<?php

global $dclmn;

$leadership = $dclmn->get_leadership();
$leadersip_emails = array_unique(array_filter(wp_list_pluck($leadership, 'email')));
asort($leadersip_emails);
?>
<?php if (current_user_can('edit_others_posts')): ?>
  <div class="button-group button-group-leadership">
    <a href="mailto:<?php echo implode(',', $leadersip_emails) ?>" class="button" target="_blank">Email</a>
    <a href="<?php echo admin_url('admin-ajax.php?action=export_leadership') ?>" class="button" target="_blank">Export</a>
  </div>
<?php endif; ?>
<table cellpadding="5" cellspacing="0" class="stripes leadership">
  <thead>
    <tr>
      <td>Office</td>
      <td>Offical</td>
      <td>Phone</td>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($leadership as $l): ?>
      <tr valign="top">
        <td class="position"><?php echo str_replace(' (', '<br><small>(', $l->post_title . '</small>') ?></td>
        <td nowrap>
          <?php
          if ($l->email) echo '<a href="mailto:' . $l->email . '" target="_blank">';
          echo $l->first_name . ' ' . $l->last_name;
          if ($l->email) echo '</a>';
          ?>
        </td>
        <td nowrap><?php echo $dclmn->get_phone_link($l->phone); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<script>
  jQuery('.button-group.button-group-leadership').insertBefore('.entry-header h1');
</script>
<style>
  .entry-header h1 {
    clear: none;
  }
</style>
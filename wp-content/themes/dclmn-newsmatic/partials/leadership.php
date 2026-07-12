<?php

global $dclmn;

$leadership = $dclmn->get_leadership();
?>
<table cellpadding="5" cellspacing="0" class="stripes leadership">
  <thead>
    <tr>
      <td>Office</td>
      <td>Official</td>
      <td>Phone</td>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($leadership as $leadership_label => $posts): ?>
      <tr valign="top">
        <td class="position"><?php echo str_replace(' (', '<br><small>(', $leadership_label . '</small>') ?></td>
        <td nowrap>
          <?php foreach ($posts as $p): ?>
            <?php
            $email = $p->email;
            if ($p->hide_email_address) $email = $p->mailbox . '@dclmn.org';
            if ($email) echo '<a href="mailto:' . $email . '" target="_blank">';
            echo $p->first_name . ' ' . $p->last_name;
            if ($email) echo '</a>';
            echo '<br>';
            ?>
          <?php endforeach; ?>
        </td>
        <td nowrap>
          <?php foreach ($posts as $p): ?>
            <?php
            if (!empty($p->phone) && !empty($p->show_phone)) {
              echo $dclmn->get_phone_link($p->phone);
              echo '<br>';
            }
            ?>
          <?php endforeach; ?>
        </td>
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
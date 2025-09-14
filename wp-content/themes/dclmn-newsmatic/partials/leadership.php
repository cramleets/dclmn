<?php

global $dclmn;

$leadership = $dclmn->get_leadership();
?>
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
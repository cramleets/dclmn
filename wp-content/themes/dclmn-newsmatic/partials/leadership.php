<?php

global $dclmn;

$leadership = $dclmn->get_leadership();
?>
<table cellpadding="5" cellspacing="0" class="stripes">
  <thead>
    <tr>
      <td>Offical</td>
      <td>Office</td>
      <td>Phone</td>
    </tr>
  </thead>
  <tbody>
    <?php foreach($leadership as $l): ?>
      <tr valign="top">
        <td>
          <?php
          if ($l->email) echo '<a href="mailto:'. $l->email .'" target="_blank">';
          echo $l->first_name .' '. $l->last_name;
          if ($l->email) echo '</a>';
          ?>
        </td>
        <td><?php echo $l->post_title ?></td>
        <td><?php echo $dclmn->get_phone_link($l->phone); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
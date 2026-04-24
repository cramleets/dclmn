<?php
//Template Name: Check-In Sheet 
$precincts = $dclmn->get_committee_people_table(true);
array_shift($precincts);
?>
<style>
  body,
  td {
    font-size: 14px;
    font-family: tahoma;
  }
</style>
<?php if (!dclmn_user_is_exec()): ?>
  <h2>You do not have permission to view this.</h2>
<?php else: ?>
  <h2>DCLMN Meeting Sign In Sheet</h2>
  <table cellpadding="5" cellspacing="0" border="1" width="100%">
    <tr style="font-weight: bold; background-color: #333; color: #fff;">
      <td width="180">Precinct</td>
      <td width="180">Name</td>
      <td>Signature</td>
    </tr>
    <?php foreach ($precincts as $precinct): ?>
      <tr>
        <td><?php echo $precinct[0] ?></td>
        <td><?php echo $precinct[3] ?> <?php echo $precinct[4] ?></td>
        <td>&nbsp;</td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>
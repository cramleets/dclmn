<?php
global $dclmn;
$dclmn_user = dclmn_get_user();
?>
<?php if (is_object($dclmn_user)): ?>
  <?php $voters = $dclmn_user->get_voters(); ?>
  <p class="dashboard-button"><a href="<?php echo home_url('cp/') ?>" class="button">Back to Dashboard</a></p>
  <div class="dclmn-contacts precinct-voters-table">
    <table cellpadding="5" cellspacing="0" class="stripes" border="1">
      <?php
      $out = '';
      foreach ($voters as $party => $voters) {
        $out .= '<thead>';
        $out .= '<tr><td colspan="100"><h1 data-party="'. $party .'">PARTY: '. $party .'</h1></td></tr>';
        $out .= '<tr class="header-row" data-party="'. $party .'">';
        foreach ($voters[0] as $k => $v) {
          $out .= '<td>' . $k . '</td>';
        }
        $out .= '</tr>';
        $out .= '</thead>';
        $out .= '<tbody data-party="'. $party .'">';
        foreach ($voters as $voter) {
          $out .= '<tr>';
          foreach ($voter as $k => $v) {
            $out .= '<td data-label="' . $k . '">' . $v . '</td>';
          }
          $out .= '</tr>';
        }
        $out .= '</tbody>';
      }
      echo $out;
      ?>
    </table>
  </div>
<?php endif; ?>
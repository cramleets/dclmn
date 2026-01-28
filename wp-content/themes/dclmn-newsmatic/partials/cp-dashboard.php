<?php
global $dclmn;
$dclmn_user = dclmn_get_user();
$out = $extra_content = $class = '';

if (!empty($_SESSION) && $_SESSION['dclmn_user_message'] || !empty($_GET['msg'])) {
  $class = (dclmn_auth('cp')) ? 'session-login-message' : 'session-login-error';
  $msg = ($_SESSION['dclmn_user_message']) ?: $_GET['msg'];
  $extra_content = $msg;
  unset($_SESSION['dclmn_user_message']);
}

$user_emails = [];
$dclmn_users = $dclmn->get_committee_people_table(true);
array_shift($dclmn_users);
$user_emails = array_unique(array_filter(array_column($dclmn_users, 5)));
asort($user_emails);

$leadership = $dclmn->get_leadership();
$leadersip_emails = array_unique(array_filter(wp_list_pluck($leadership, 'email')));
asort($leadersip_emails);

if ($dclmn_user || !empty($extra_content)) {
  $out .= '<div class="cp-dashboard-statusbar">';
  if (!$dclmn_user && !empty($extra_content)) {
    $out .= '<div class="' . $class . '">' . $extra_content . '</div>';
  } elseif ($dclmn_user) {
    $out .= '<div>';
    $out .= '<span class="welcome">Welcome ' . $dclmn_user->first_name . '</span>';
    $out .= '</div>';
    $out .= '<div>';
    if (current_user_can('edit_posts')) {
      $out .= '<a href="' . get_edit_post_link($dclmn_user->ID) . '" target="_blank" class="button">Edit</a>';
    }
    $out .= '<a href="' . home_url('cp/?action=cp-logout&cb=' . uniqid()) . '" class="button" onclick="return confirm(\'Are you sure?\');">Log Out</a>';
    $out .= '</div>';
  }
  $out .= '</div>';
  $out .= '<div class="clear"></div>';
}
?>
<?php echo $out; ?>
<?php if (!is_object($dclmn_user)): ?>
  <form method="post" id="cp-login-form">
    <p>To log in as a DCLMN Committee Person or Leadership, please enter your email address.</p>
    <input type="text" name="email" id="cp-email">
    <input type="submit" value="go" id="cp-login-trigger" class="button">
    <div id="cp-login-result"></div>
  </form>
  <script>
    jQuery('#cp-email').focus();
  </script>
<?php else: ?>
  <div class="user-info">
    <h3>Your Precinct</h3>
    <h2><?php echo $dclmn_user->get_precinct()->post_title; ?></h2>
    <hr>
    <h3>Your Information</h3>
    <p class="note">Please contact <?php echo dclmn_board_member_email_link('Subcommittee Chair–Technology', 'DCLMN Contact Info') ?> if you need any of this information updated.</p>
    <table cellpadding="5" cellspacing="0" class="stripes">
      <tr>
        <td>Email</td>
        <td><?php echo $dclmn_user->get_email(); ?></td>
      </tr>
      <tr>
        <td>Phone</td>
        <td><?php echo $dclmn_user->get_phone(); ?></td>
      </tr>
      <tr>
        <td>Address</td>
        <td><?php echo $dclmn_user->get_address(); ?></td>
      </tr>
    </table>
  </div>
  <hr>
  <div class="dclmn-tools">
    <h3>DCLMN Tools & Resources</h3>
    <ul>
    <?php if ($dclmn_user->is_exec()): ?>
      <li><a href="<?php echo home_url('cp/dclmn-contacts/') ?>">DCLMN Contacts</a></li>
    <?php endif; ?>
      <li><a href="<?php echo home_url('cp/cps/') ?>">View CPs</a></li>
      <li><a href="mailto:?bcc=<?php echo implode(',', $user_emails) ?>" target="_blank">Email CPs</a></li>
      <!-- <li><a href="<?php echo admin_url('admin-ajax.php?action=export_cps') ?>">Export CPs</a></li> -->
      <li><a href="mailto:?bcc=<?php echo implode(',', $leadersip_emails) ?>" target="_blank">Email Leadership</a></li>
      <!-- <li><a href="<?php echo admin_url('admin-ajax.php?action=export_leadership') ?>">Export Leadership</a></li> -->
      <li><a href="<?php echo home_url('cp/precinct-voters/') ?>">My Voters</a></li>
    </ul>
  </div>
  <hr>
  <h3>Meetings</h3>
  <?php
  $meeting_events_args = [
    // 'header' => 'Meetings',
    'category' => 'meetings',
    'posts_per_page' => 2,
  ];

  echo dclmn_homepage_events($meeting_events_args);
  ?>
<?php endif; ?>
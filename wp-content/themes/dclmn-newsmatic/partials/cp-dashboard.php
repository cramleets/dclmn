<?php
global $dclmn;
$dclmn_user = dclmn_get_user();
$out = $extra_content = $class = '';

if (!empty($_SESSION) && !empty($_SESSION['dclmn_user_message']) || !empty($_GET['msg'])) {
  $class = (dclmn_auth('cp')) ? 'session-login-message' : 'session-login-error';
  $msg = ($_SESSION['dclmn_user_message']) ?: $_GET['msg'];
  $extra_content = $msg;
  unset($_SESSION['dclmn_user_message']);
}

// $user_emails = [];
// $dclmn_users = $dclmn->get_committee_people_table(true);
// array_shift($dclmn_users);
// $user_emails = array_unique(array_filter(array_column($dclmn_users, 5)));
// asort($user_emails);

// $leadersip_emails = $dclmn->get_leadership_emails();
// asort($leadersip_emails);

if ($dclmn_user || !empty($extra_content)) {
  $out .= '<div class="cp-dashboard-statusbar">';
  if (!$dclmn_user && !empty($extra_content)) {
    $out .= '<div class="' . $class . '">' . $extra_content . '</div>';
  } elseif ($dclmn_user) {
    $out .= '<div>';
    $out .= '<span class="welcome">Welcome ' . $dclmn_user->first_name . '</span>';
    $out .= '</div>';
  }
  $out .= '</div>';
  $out .= '<div class="clear"></div>';
}
?>
<?php if ($dclmn_user) get_template_part('partials/cp-nav'); ?>
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
    <?php get_template_part('partials/dashboard/tools', NULL, ['dclmn_user'=>$dclmn_user]); ?>
    <?php get_template_part('partials/dashboard/cp-info', NULL, ['dclmn_user'=>$dclmn_user]); ?>
    <?php get_template_part('partials/dashboard/exec-info', NULL, ['dclmn_user'=>$dclmn_user]); ?>
  </div>
<?php endif; ?>
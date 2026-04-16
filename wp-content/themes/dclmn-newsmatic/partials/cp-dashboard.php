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
    <h3>Your Precinct</h3>
    <h2><?php echo $dclmn_user->get_precinct()->post_title; ?></h2>
    <hr>
    <h3>Your Information</h3>
    <p class="note">Please contact <?php echo dclmn_board_member_email_link('Subcommittee Chair – Technology', 'DCLMN Contact Info') ?> if you need any of this information updated.</p>
    <table cellpadding="5" cellspacing="0" class="stripes">
      <tr>
        <td width="180">Email</td>
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
    <br>
    <table cellpadding="5" cellspacing="0" class="">
      <tr>
        <td width="180">
          <input type="checkbox" id="hide-cp-email-address" <?php if ($dclmn_user->email_address_is_hidden()) echo 'checked' ?>>
        </td>
        <td>
          <label for="hide-cp-email-address"><strong>Do Not Publish My Email Address</strong></label>
          <span id="hide-cp-email-address-result"></span>
        </td>
      </tr>
    </table>
  </div>
  <hr>
  <div class="dclmn-tools">
    <div class="flex">
      <div>
        <h3>Election Resources</h3>
        <ul>
          <li><a href="https://drive.google.com/drive/folders/1aKBNH8LehMBqKRV_xzOli_XcCN9eWjkB" target="_blank" style="font-size: 1.25em; font-weight: 700;">Petitions</a></li>
        </ul>
        <?php if ($documents = dclmn_get_posts(['post_type' => 'document', 'posts_per_page' => -1])): ?>
          <h3>DCLMN Documents</h3>
          <ul>
            <?php foreach ($documents as $post): ?>
              <li><a href="<?php echo $post->href ?>" target="_blank"><?php echo $post->post_title ?></a></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <h3>DCLMN Tools & Resources</h3>
        <ul>
          <li><a href="<?php echo home_url('cp/cps/') ?>">View and Contact Committee People</a></li>
          <li><a href="<?php echo home_url('cp/leadership/') ?>">View and Contact Leadership</a></li>
          <?php if (current_user_can('edit_published_contacts')): ?>
            <li><a href="<?php echo home_url('cp/dclmn-contacts/') ?>">View and Contact DCLMN Contacts</a></li>
          <?php elseif ($dclmn_user->is_exec()): ?>
            <li><a href="<?php echo home_url('cp/dclmn-contacts/') ?>">View DCLMN Contacts</a></li>
          <?php endif; ?>
          <li><a href="<?php echo home_url('subcommittees/') ?>">DCLMN Subcommittees</a></li>
          <li><a href="<?php echo get_stylesheet_directory_uri() ?>/assets/dclmn-guide-to-welcoming-new-residents.pdf" target="_blank">DCLMN Guide to Welcoming New Residents</a></li>
          <?php if (current_user_can('edit_others_posts')): ?>
            <li><a href="<?php echo home_url('cp/precinct-voters/') ?>">My Voters</a></li>
            <li><a href="<?php echo home_url('cp/check-in-sheet/') ?>">Meeting Check-In Sheet</a></li>
          <?php endif; ?>
        </ul>
        <h3>MCDC Tools</h3>
        <ul>
          <li>
            <a href="https://mcdems.org/cpc/">MCDC Committee Person Center</a>
            <ul>
              <li>Committee Person Hand Book</li>
              <li>MCDC By-laws</li>
              <li>CP Appointment Form</li>
              <li>CP Resignation Form</li>
              <li>Executive Committee Proxy Form</li>
              <li>Endorsement Convention Proxy</li>
            </ul>
          </li>
        </ul>
      </div>
      <div>
        <h3>Meetings</h3>
        <?php
        $meeting_events_args = [
          // 'header' => 'Meetings',
          'category' => 'meetings',
          'posts_per_page' => 2,
        ];

        echo dclmn_homepage_events($meeting_events_args);
        ?>
        <?php get_template_part('partials/zoom-meetings') ?>
      </div>
    </div>
  </div>
<?php endif; ?>
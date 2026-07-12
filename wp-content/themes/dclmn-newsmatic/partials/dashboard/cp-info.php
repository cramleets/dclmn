<?php $dclmn_user = $args['dclmn_user']; ?>
<?php if ($dclmn_user->is_cp()): ?>
  <div class="dashboard-group">
    <h3>Your CP Information</h3>
    <h2><?php echo $dclmn_user->cp->get_precinct()->post_title; ?></h2>
    <p class="note">Please contact <?php echo dclmn_board_member_email_link('Subcommittee Chair - Technology', 'DCLMN Contact Info') ?> if you need any of this information updated.</p>
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
          <input type="checkbox" class="hide-user-email-address" id="hide-user-email-address-<?php echo $dclmn_user->cp->ID ?>" data-post_id="<?php echo $dclmn_user->cp->ID ?>" <?php if ($dclmn_user->cp->email_address_is_hidden()) echo 'checked' ?>>
        </td>
        <td>
          <label for="hide-user-email-address-<?php echo $dclmn_user->cp->ID ?>"><strong>Do Not Publish My Email Address</strong></label>
          <span id="hide-user-email-address-result-<?php echo $dclmn_user->cp->ID ?>"></span>
        </td>
      </tr>
    </table>
    <br>
    <table cellpadding="5" cellspacing="0" class="">
      <tr>
        <td width="180"></td>
        <td>Emails sent to <a href="mailto:<?php echo $dclmn_user->cp->get_mailbox(); ?>" target="_blank"><strong><?php echo $dclmn_user->cp->get_mailbox(); ?></strong></a> will be forwarded to you.</td>
      </tr>
    </table>
  </div>
<?php endif; ?>
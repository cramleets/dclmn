<?php $dclmn_user = $args['dclmn_user']; ?>
<?php if ($dclmn_user->is_exec()): ?>
  <div class="dashboard-group">
    <h2>Executive Committee Position<?php if (count($dclmn_user->positions) != 1): ?>s<?php endif; ?></h2>
    <?php foreach ($dclmn_user->positions as $position): ?>
      <div class="committee-position">
        <h3><?php echo $position->position_label ?></h3>
        <table cellpadding="5" cellspacing="0" class="stripes">
          <tr>
            <td width="180">Email</td>
            <td><?php echo $position->email; ?></td>
          </tr>
          <tr>
            <td>Phone</td>
            <td><?php echo $position->phone; ?></td>
          </tr>
        </table>
        <?php if (1): ?>
          <br>
          <table cellpadding="5" cellspacing="0" class="">
            <tr>
              <td width="180">
                <input type="checkbox" class="hide-user-email-address" id="hide-user-email-address-<?php echo $position->ID ?>" data-post_id="<?php echo $position->ID ?>" <?php if ($position->email_address_is_hidden()) echo 'checked' ?>>
              </td>
              <td>
                <label for="hide-user-email-address-<?php echo $position->ID ?>"><strong>Do Not Publish My Email Address</strong></label>
                <span id="hide-user-email-address-result-<?php echo $position->ID ?>"></span>
              </td>
            </tr>
          </table>
        <?php endif; ?>
        <?php if (!empty($position->mailbox)): ?>
          <br>
          <table cellpadding="5" cellspacing="0" class="">
            <tr>
              <td width="180"></td>
              <td>Emails sent to <a href="mailto:<?php echo $position->get_mailbox(); ?>" target="_blank"><strong><?php echo $position->get_mailbox(); ?></strong></a> will be forwarded to you.</td>
            </tr>
          </table>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
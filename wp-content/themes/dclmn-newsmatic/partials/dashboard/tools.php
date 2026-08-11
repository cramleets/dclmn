<?php
$dclmn_user = $args['dclmn_user'];

$zoom = new DCLMN_Zoom_API();
$meetings = $zoom->get_meetings();
foreach($meetings as $meeting) {
  $dt = new DateTime($meeting['start_time']);
  $dt->setTimezone(new DateTimeZone($meeting['timezone']));
  $meeting_date = $dt->format('l, F j, Y');
  $meeting_time = $dt->format('g:i a');

  if (stristr($meeting['topic'], 'general meeting') && $dt->format('Y-m-d') === date('Y-m-d')) {
    $todays_meeting = '';
    $todays_meeting .= '<div class="todays-meeting">';
    $todays_meeting .= '<h3>Tonight\'s Meeting - '. $meeting_time .'</h3>';
    $todays_meeting .= '<div class="todays-meeting-padding">';
    $todays_meeting .= '<a href="'. $meeting['join_url'] .'" target="_blank">';
    $todays_meeting .= '<span>'. $meeting['topic'] .'</span>';
    $todays_meeting .= '<span><strong>Click Here to Join the Meeting</strong></span>';
    $todays_meeting .= '</a>';
    // $todays_meeting .= '<br><span>'. $meeting['join_url'] .'</span>';
    $todays_meeting .= '</div>';
    $todays_meeting .= '</div>';

    break;
  }
}
?>
<div class="dclmn-tools">
  <?php echo $todays_meeting; ?>
  <div class="flex">
    <div>
      <?php if ($documents = dclmn_get_posts(['post_type' => 'document', 'posts_per_page' => -1])): ?>
        <h3>DCLMN Documents</h3>
        <ul>
          <?php foreach ($documents as $post): ?>
            <li><a href="<?php echo $post->href ?>" target="_blank"><?php echo $post->post_title ?></a></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <h3>DCLMN Tools</h3>
      <ul>
        <li><a href="<?php echo home_url('cp/cps/') ?>">View and Contact Committee People</a></li>
        <li><a href="<?php echo home_url('cp/leadership/') ?>">View and Contact Leadership</a></li>
        <li><a href="https://www.votebuilder.com/" target="_blank">Vote Builder</a></li>
        <li><a href="<?php echo home_url('streetlists/') ?>" target="_blank">Street Lists</a></li>
      </ul>
      <h3>DCLMN Resources</h3>
      <ul>
        <li><a href="<?php echo home_url('document/roberts-rules-cheat-sheet/') ?>" target="_blank">Robert's Rules Cheat Sheet</a></li>
        <li><a href="<?php echo home_url('document/dclmn-guide-to-welcoming-new-residents/') ?>" target="_blank">Guide to Welcoming New Residents</a></li>
        <li><a href="https://drive.google.com/drive/folders/1aKBNH8LehMBqKRV_xzOli_XcCN9eWjkB" target="_blank">Petitions</a></li>
        <li><a href="<?php echo home_url('subcommittees/') ?>" target="_blank">Subcommittees</a></li>
        <li>
          Generic Proxy Form:
          <a href="<?php echo home_url('document/dclmn-proxy-form-pdf/') ?>" target="_blank">PDF</a> |
          <a href="<?php echo home_url('document/dclmn-proxy-form-word/') ?>" target="_blank">Word Doc</a>
        </li>
      </ul>
      <?php if (dclmn_user_is_exec()): ?>
        <h3>Exec Tools</h3>
        <ul>
          <?php if (current_user_can('update_core')): ?>
            <li><a href="<?php echo home_url('cp/dclmn-contacts/') ?>">View and Contact DCLMN Contacts</a></li>
          <?php endif; ?>
          <li><a href="<?php echo home_url('cp/check-in-sheet/') ?>" target="_blank">Meeting Check-In Sheet</a></li>
          <li><a href="<?php echo home_url('cp/room-request/') ?>" target="_blank">Event/Meeting Room Request</a></li>
        </ul>
      <?php endif; ?>

      <?php if (current_user_can('edit_others_posts')): ?>
        <h3>Testing Tools</h3>
        <ul>
          <li><a href="<?php echo home_url('cp/precinct-voters/') ?>">My Voters</a></li>
        </ul>
      <?php endif; ?>
      <h3>MCDC Tools</h3>
      <ul>
        <li>
          <strong><a href="https://mcdems.org/cpc/" target="_blank">MCDC Committee Person Center</a></strong>
          <div><strong>Here you can find the latest...</strong></div>
          <ul>
            <li>Committee Person Hand Book</li>
            <li>MCDC By-laws</li>
            <li>CP Appointment Form</li>
            <li>CP Resignation Form</li>
            <li>Executive Committee Proxy Form</li>
            <li>Endorsement Convention Proxy Form</li>
          </ul>
        </li>
      </ul>
      <h3>Exec Board Links</h3>
      <?php if ($dclmn_user->is_exec()): ?>
        <ul>
          <li><a href="<?php echo home_url('cp/room-request/') ?>">Event/Meeting Room Request</a></li>
        </ul>
      <?php endif; ?>
      <h3>Miscellaneous</h3>
      <ul>
        <li><a href="<?php echo home_url('lower-merion-street-name-generator/') ?>" target="_blank">Lower Merion Street Name Generator</a></li>
      </ul>
    </div><!-- end left column -->
    <div><!-- begin right column -->
      <h3>Meetings</h3>
      <?php
      $meeting_events_args = [
        // 'header' => 'Meetings',
        'category' => 'meetings',
        'posts_per_page' => 2,
      ];

      echo dclmn_homepage_events($meeting_events_args);
      ?>
      <h3>Canvassing</h3>
      <?php
      $meeting_events_args = [
        // 'header' => 'Meetings',
        'category' => 'canvassing',
        'posts_per_page' => 4,
      ];

      echo dclmn_homepage_events($meeting_events_args);
      ?>
      <?php get_template_part('partials/zoom-meetings') ?>
    </div>
  </div>
</div>
<?php $dclmn_user = $args['dclmn_user']; ?>
<div class="dclmn-tools">
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
        <li><a href="https://local.dclmn.us/streetlists/" target="_blank">Street Lists</a></li>
      </ul>
      <h3>DCLMN Resources</h3>
      <ul>
        <li><a href="<?php echo get_stylesheet_directory_uri() ?>/assets/dclmn-roberts-rules-cheat-sheet.pdf" target="_blank">Robert's Rules Cheat Sheet</a></li>
        <li><a href="<?php echo get_stylesheet_directory_uri() ?>/assets/dclmn-guide-to-welcoming-new-residents.pdf" target="_blank">Guide to Welcoming New Residents</a></li>
        <li><a href="https://drive.google.com/drive/folders/1aKBNH8LehMBqKRV_xzOli_XcCN9eWjkB" target="_blank">Petitions</a></li>
        <li><a href="<?php echo home_url('subcommittees/') ?>" target="_blank">Subcommittees</a></li>
        <li>
          Generic Proxy Form:
          <a href="<?php echo get_stylesheet_directory_uri() ?>/assets/dclmn-proxy-form-generic.pdf" target="_blank">PDF</a> |
          <a href="<?php echo get_stylesheet_directory_uri() ?>/assets/dclmn-proxy-form-generic.docx" target="_blank">Word Doc</a>
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
      <h3>Miscellaneous</h3>
      <ul>
        <li><a href="<?php echo home_url('lower-merion-street-name-generator/') ?>" target="_blank">Lower Merion Street Name Generator</a></li>
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
  <?php if ($dclmn_user->is_exec()): ?>
    <p><a href="<?php echo home_url('cp/room-request/') ?>" class="button">Event/Meeting Room Request</a></p>
  <?php endif; ?>
</div>
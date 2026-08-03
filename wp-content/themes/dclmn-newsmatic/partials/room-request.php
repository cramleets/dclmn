<?php
global $dclmn;
$dclmn_user = dclmn_get_user();
?>
<?php if (is_object($dclmn_user)): ?>
  <?php get_template_part('partials/cp-nav'); ?>
  <?php if (dclmn_user_is_exec()): ?>
    <div class="flex room-request-flex">
      <div>
        <?php echo do_shortcode('[formidable id=5]'); ?>
      </div>
      <div>
        <?php get_template_part('partials/room-request-calendar'); ?>
        <br>
        <?php echo get_room_reservation_events_google($dclmn->room_reservations_calendar_url) ?>
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>
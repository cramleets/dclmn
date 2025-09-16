<div class="button-group">
  <a href="<?php echo home_url('congressional-and-state-officials/') ?>">Congressional & State</a>
  <a href="<?php echo home_url('county-elected-officials/') ?>">County</a>
  <a href="<?php echo home_url('local-elected-officials/') ?>">Local</a>
</div>

<div class="elected-officials-accordion">
  <?php
  // get_template_part('partials/elected-officials');
  // get_template_part('partials/county');
  // get_template_part('partials/local');
  ?>
</div>
<style>
  .elected-officials-accordion .officials-table {
    display: none;
  }

  .elected-officials-accordion .officials-table.expanded {
    display: flex;
  }

  .elected-officials-accordion .officials-table-wrap:not(:first-of-type) h2 {
    margin-top: 0;
  }

  .elected-officials-accordion .officials-table-wrap h2:hover {
    color: var(--color-highlight);
    border-color: var(--color-highlight);
    cursor: pointer;
  }
</style>
<script>
  jQuery(document).ready(function($) {
    $('.elected-officials-accordion .officials-table-wrap h2').on('click', function() {
      $(this).closest('.officials-table-wrap').find('.officials-table').toggleClass('expanded');
    });
  });
</script>
<?php require_once get_stylesheet_directory() . '/inc/functions.dclmn-admin.php'; ?>
<style>
  .import-results,
  .wrap fieldset {
    border: 1px #ccc solid;
    padding: 1em;
    background-color: #fff;
  }

   .import-results {
    max-height: 300px;
    overflow: auto;
  }
</style>
<div class="wrap">
  <div id="icon-tools" class="icon32"></div>
  <h2>NAPCO &raquo; Editor's Tools</h2>
  <?php dclmn_committee_people_import_page() ?>
</div>
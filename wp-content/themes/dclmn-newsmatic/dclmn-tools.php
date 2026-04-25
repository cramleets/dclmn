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

  #contacts-export label:not(:last-of-type) {
    border-right: 1px #000 solid;
    margin-right: .5em;
    padding-right: .5em;
  }

  #contacts-export label {
    padding: .25em 0;
  }

  #contacts-export label input {
    vertical-align: middle;
  }

  #contacts-export label:hover {
    background-color: #ffc;
    cursor: pointer;
  }

  #contacts-export p {
    border-bottom: 1px #ccc solid;
    padding-bottom: .5em;
  }

  #contacts-export strong {
    display: inline-block;
    width: 120px;
    text-align: right;
    margin-right: .5em;
  }
</style>
<div class="wrap">
  <div id="icon-tools" class="icon32"></div>
  <h2>NAPCO &raquo; Editor's Tools</h2>
  <fieldset>
    <h2>Export Contacts CSV</h2>
    <form method="POST" target="_blank" action="<?php echo admin_url('admin-ajax.php?action=export_contacts') ?>" id="contacts-export">
      <p>
        <strong>DCLMN</strong>
        <label><input type="checkbox" name="contacts[dclmn][cps]" value="1"> Committee People</label>
        <label><input type="checkbox" name="contacts[dclmn][leadership]" value="1"> Leadership</label>
      </p>
      <p>
        <strong>Elected Officials</strong>
        <?php
        foreach (get_terms(['taxonomy' => 'jurisdiction', 'hide_empty' => true]) as $term) {
          echo '<label><input type="checkbox" name="contacts[elected_officials][]" value="' . $term->term_id . '"> ' . $term->name . '</label> ';
        }
        ?>
      </p>
      <p>
        <strong>Contacts</strong>
        <?php
        foreach (get_terms(['taxonomy' => 'contact_type', 'hide_empty' => true]) as $term) {
          echo '<label><input type="checkbox" name="contacts[contacts][]" value="' . $term->term_id . '"> ' . $term->name . '</label> ';
        }
        ?>
      </p>
      <input type="submit" class="button-primary" value="Export Contacts">
    </form>
  </fieldset>
  <br>
  <?php dclmn_committee_people_import_page() ?>
  <br>
  <?php dclmn_contacts_import_page() ?>
  <br>
  <fieldset>
    <h2>Cron Triggers</h2>
    <a href="<?php echo home_url('dclmn-data-populator/') ?>" target="_blank" class="button-primary" style="font-size: 1.5em; font-weight: bold;">Run The ARCGIS Data Populator</a>
    <a href="<?php echo home_url('dclmn-cpanel-forwards-populator/') ?>" target="_blank" class="button-primary" style="font-size: 1.5em; font-weight: bold;" onclick="return confirm('This will take about ten minutes to complete.');">Run The Cpanel Email Forwards Populator</a>
  </fieldset>
  <br>
  <?php 
    $cpapi = new DCLMN_Cpanel_API();
    $forwarders = $cpapi->get_forwarders();
    $out = '';
    $out .= '<table class="wp-list-table widefat fixed striped table-view-list posts">';
    $out .= '<thead>';
    $out .= '<tr>';
    foreach($forwarders[0] as $k=>$v) {
      $out .= '<th>'. $k .'</th>';
    }
    $out .= '</tr>';
    $out .= '</thead>';
    $out .= '<tbody>';
    foreach($forwarders as $forwarder) {
      $out .= '<tr>';
      foreach($forwarder as $k=>$v) {
        $out .= '<td>'. $v .'</td>';
      }
     $out .= '</tr>';
    }
    $out .= '</tbody>';
    $out .= '</table>';
    echo $out;
  ?>
</div>
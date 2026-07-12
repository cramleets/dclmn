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

  .widefat thead tr.forwarder-type td {
    background-color: #000 !important;
    color: #fff !important;
    font-weight: bold;
    font-size: 1.25em;
    text-transform: uppercase;
  }

  #forwarders tbody {
    display: block;
    height: 250px;
    overflow: auto;
  }

  #forwarders thead,
  #forwarders tbody tr {
    display: table;
    width: 100%;
    table-layout: fixed;
  }

  #forwarders thead {
    width: calc(100% - 1em)
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
  $path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/logs/cp-logins';
  $file = file_get_contents(trim($path) . '/cp-logins.log');
  $file = explode("\n", trim($file));
  $file = array_map(function ($x) {
    return explode("\t", trim($x));
  }, $file);
  $file = array_filter(array_reverse($file));

  $out = '';
  $out .= '<h2>Dashboard Logins</h2>';
  $out .= '<div style="max-height: 200px; overflow: auto;">';
  $out .= '<table class="wp-list-table widefat fixed striped table-view-list posts">';
  $out .= '<thead>';
  $out .= '<tr>';
  $out .= '<th>#</th>';
  $out .= '<th>Date</th>';
  $out .= '<th>IP Address</th>';
  $out .= '<th>Action</th>';
  $out .= '<th>Email Hashed</th>';
  $out .= '<th>Email</th>';
  $out .= '<th>ID</th>';
  $out .= '<th>UA</th>';
  $out .= '</tr>';
  $out .= '</thead>';

  $out .= '<tbody>';

  $i = 0;
  foreach ($file as $login) {
    $i++;
    $out .= '<tr>';
    $out .= '<td style="text-align: right;">' . $i . '</td>';
    foreach ($login as $k => $v) {
      $out .= '<td>' . $v . '</td>';

      if (3 == $k) {
        $out .= '<td>' . unserialize(base64_decode($v)) . '</td>';
      }
    }
    $out .= '</tr>';
  }
  $out .= '</tbody>';
  $out .= '</table>';
  $out .= '</div>';

  echo $out;
  ?>
  <br>
  <?php
  $cpapi = new DCLMN_Cpanel_API();
  $forwarders = $cpapi->get_sorted_forwarders();
  $first_key = array_key_first($forwarders);
  $forwarders_keys = array_keys($forwarders[$first_key][0]);

  $out = '';
  $out .= '<h2>Cpanel Forwarders</h2>';
  if (!count($forwarders)) {
    $out .= 'None found.';
  } else {
    $out .= '<div id="forwarders">';
    $out .= '<table class="wp-list-table widefat fixed striped table-view-list posts">';

    foreach ($forwarders as $key => $key_forwarders) {
      $out .= '<thead>';
      $out .= '<tr class="forwarder-type""><td colspan="8">' . $key . '</td></tr>';
      $out .= '<tr>';
      $out .= '<th style="text-align: right;">#</th>';
      $out .= '<th>Type</th>';
      foreach ($forwarders_keys as $v) {
        $out .= '<th>' . $v . '</th>';
      }
      $out .= '</tr>';
      $out .= '</thead>';
      $out .= '<tbody>';

      $i = 0;
      foreach ($key_forwarders as $forwarder) {
        $i++;

        $out .= '<tr>';
        $out .= '<td width="50" style="text-align: right;">' . $i . '</td>';
        $out .= '<td width="50">' . $key . '</td>';
        foreach ($forwarder as $k => $v) {
          $out .= '<td>' . $v . '</td>';
        }
        $out .= '</tr>';
      }
      $out .= '</tbody>';
    }
    $out .= '</table>';
    $out .= '</div>';
  }
  echo $out;
  ?>
</div>
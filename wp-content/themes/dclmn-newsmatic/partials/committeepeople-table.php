<?php
global $dclmn;
$dclmn_user = dclmn_get_user();
?>
<?php if (is_object($dclmn_user) && $dclmn_user->is_exec()): ?>
  <?php
  $precincts = $dclmn->get_committee_people_table('raw');
  function get_address($post) {
    $out = '';

    $out .= ($post->street_address_1) ? $post->street_address_1 . '<br>' : '';
    $out .= ($post->street_address_2) ? $post->street_address_2 . '<br>' : '';
    $out .= ($post->street_address_3) ? $post->street_address_3 . '<br>' : '';

    $out .= ($post->city) ? $post->city : '';
    if ($post->state) {
      if ($post->city) $out .= ', ';
      $out .= $post->state;
    }
    if ($post->zip) {
      if ($post->city || $post->state) $out .= ' ';
      $out .= $post->zip;
    }

    return $out;
  }

  $out = '';

  $out .= '<table border cellpadding="5" cellspacing="0" class="stripes committee-people small">';
  $out .= '<thead>';
  $out .= '<tr valign="top">';
  $out .= '<td data-label="Email All" style="text-align: center;"><input type="checkbox" class="email-checkbox-all" style="vertical-align: middle;"></td>';
  $out .= '<td>Precinct</td>';
  $out .= '<td>Region</td>';
  $out .= '<td>District</td>';
  $out .= '<td>First Name</td>';
  $out .= '<td>Last Name</td>';
  $out .= '<td>Email</td>';
  $out .= '<td>Phone</td>';
  $out .= '<td>Address</td>';
  $out .= '<td>Polling Place</td>';
  if (current_user_can('edit_others_posts')) {
    $out .= '<td>&nbsp;</td>';
  }
  $out .= '</tr>';
  $out .= '</thead>';
  $out .= '<tbody>';

  foreach ($precincts as $precinct) {

    foreach ($precinct->committe_people as $person) {
      $vacant = ('vacant' == strtolower($person->first_name));
      $district = str_replace('th district', '', strtolower($precinct->pa_district->post_title));

      $title = $precinct->post_title;
      $title = str_replace('Narberth ', 'N-', $title);
      $title = str_replace('Lower Merion ', '', $title);

      $site_name = (!empty($precinct->polling_place->custom_label)) ? $precinct->polling_place->custom_label : $precinct->polling_place->site_name;

      $out .= '<tr valign="top" data-region="' . $precinct->region . '">';
      if ($vacant) {
        $out .= '<td>&nbsp;</td>';
      } else {
        $out .= '<td data-label="Send Email" style="text-align: center;"><input type="checkbox" class="email-checkbox" data-post_id="" ' . $person->ID . '"></td>';
      }
      $out .= '<td data-label="Precinct"" class="precinct searchable">' . $title . '</td>';
      $out .= '<td data-label="Region" style="text-align: center;">' . substr($precinct->region, 0, 1) . '</td>';
      $out .= '<td data-label="District" class="searchable">' . $precinct->pa_district->district . '</td>';
      if ($vacant) {
        $out .= '<td colspan="5" class="vacant">Vacant</td>';
      } else {
        $out .= '<td data-label="First Name" class="searchable">' . $person->first_name . '</td>';
        $out .= '<td data-label="Last Name" class="searchable">' . $person->last_name . '</td>';
        $out .= '<td data-label="Email"><a href="mailto:' . $person->public_email . '" target="_blank">' . $person->public_email . '</a></td>';
        $out .= '<td data-label="Phone"><a href="tel:' . $person->phone . '">' . $person->phone . '</a></td>';
        $out .= '<td data-label="Address">' . get_address($person) . '</td>';
      }
      $out .= '<td data-label="Polling Place"><a href="' . $dclmn->map_url($precinct->polling_place) . '" target="_blank">' . $site_name . '</a></td>';
      if (current_user_can('edit_others_posts')) {
        $out .= '<td><a href="' . get_edit_post_link($person->ID) . '" target="_blank">edit</a></td>';
      }
      $out .= '</tr>';
    }
  }
  $out .= '</tbody>';
  $out .= '</table>';
  ?>
  <?php get_template_part('partials/modal-email-to-bcc'); ?>
  <?php get_template_part('partials/cp-nav'); ?>
  <div class="contact-table-toggles">
    <input id="cps-search-terms" placeholder="Search">
    <span class="cp-search-terms">
      <?php foreach (get_terms(['taxonomy' => 'dclmn_region', 'hide_empty' => true]) as $term): ?>
        <label><input type="checkbox" class="regions-toggle" data-region="<?php echo $term->name ?>" checked> <?php echo $term->name ?></label>
      <?php endforeach; ?>
    </span>
    <span class="cp-table-buttons">
      <span> | </span>
      <a href="<?php echo admin_url('admin-ajax.php?action=export_cps_full') ?>" class="button export-cps" target="_blank">Export</a>
      <span> | </span>
      <a href="mailto:" class="button email-checked" target="_blank">Email Checked</a>
      <span> | </span>
      <a href="#" class="button copy-checked" target="_blank">Copy Checked to Clipboard</a>
    </span>
  </div>
  <?php echo $out; ?>
  <div class="contact-table-toggles">
    <a href="mailto:" class="button email-checked" target="_blank">Email Checked</a>
    <a href="#" class="button copy-checked" target="_blank">Copy Checked to Clipboard</a>
  </div>
  <script>
    jQuery(document).ready(function($) {
      $('#cps-search-terms').focus();

      $(document).on('keyup', '#cps-search-terms', function() {
        search_cps();
      });

      $(document).on('click', '.regions-toggle', function() {
        search_cps();
      });

      window.search_cps = function search_cps() {
        $el = $('#cps-search-terms');

        var search_terms = $el.val().toLowerCase();

        $('.stripes.committee-people tbody tr').each(function() {
          var $tr = $(this);
          var region = $(this).data('region');
          var show_region = false;

          $tr.hide();

          $checkbox = $('.regions-toggle[data-region=' + region + ']');
          if ($checkbox.length && $checkbox.is(':checked')) {
            show_region = true;
          }

          $tr.find('td.searchable').each(function() {
            var text = $(this).text().toLowerCase();

            if (text.indexOf(search_terms) != -1 && show_region) {
              $tr.show();
              return false;
            } else {
              $tr.find('input[type=checkbox]').prop('checked', false);
              $tr.hide();
            }
          });
        });
      }
    });
  </script>
<?php endif; ?>
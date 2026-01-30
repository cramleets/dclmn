<?php
global $dclmn;
$dclmn_user = dclmn_get_user();
?>
<?php if (is_object($dclmn_user) && $dclmn_user->is_exec()): ?>
  <p class="dashboard-button"><a href="<?php echo home_url('cp/') ?>" class="button">Back to Dashboard</a></p>
  <div class="contact-table-toggles">
    <input id="cps-search-terms" placeholder="Search">
    <?php foreach (get_terms(['taxonomy' => 'contact_type', 'hide_empty' => true]) as $term): ?>
      <label><input type="checkbox" class="contact-table-toggle" data-contact_type_id="<?php echo $term->term_id ?>" <?php if ('Active Dem' == $term->name) echo ' checked'; ?>> <?php echo $term->name ?></label>
    <?php endforeach; ?>
  </div>
  <table cellpadding="5" cellspacing="0" class="stripes contacts-table" border="1">
    <?php
    $out = '';
    foreach (get_terms(['taxonomy' => 'contact_type', 'hide_empty' => true]) as $term) {
      $out .= '<thead data-contact_type_id="' . $term->term_id . '">';
      $out .= '<tr data-contact_type_id="' . $term->term_id . '">';
      $out .= '<td nowrap>Contact Type</td>';
      $out .= '<td>First Name</td>';
      $out .= '<td>Last Name</td>';
      if (current_user_can('edit_published_contacts')) {
        $out .= '<td>Email</td>';
        $out .= '<td>Phone</td>';
      }
      $out .= '<td>Title</td>';
      $out .= '<td>Notes</td>';
      if (current_user_can('edit_published_contacts')) {
        $out .= '<td>&nbsp;</td>';
      }
      $out .= '</tr>';
      $out .= '</thead>';
      $out .= '<tbody data-contact_type_id="' . $term->term_id . '">';
      foreach ($dclmn->get_contacts($term->term_id) as $contact) {
        $out .= '<tr data-contact_type_id="' . $term->term_id . '">';
        $out .= '<td data-label="Contact Type" nowrap>' . $term->name . '</td>';
        $out .= '<td data-label="First Name" class="searchable">' . $contact->first_name . '</td>';
        $out .= '<td data-label="Last Name" class="searchable">' . $contact->last_name . '</td>';
        if (current_user_can('edit_published_contacts')) {
          $out .= '<td data-label="Email" class="searchable"><a href="mailto:' . $contact->email . '" target="_blank">' . $contact->email . '</a></td>';
          $out .= '<td data-label="Phone" class="searchable">' . $contact->phone . '</td>';
        }
        $out .= '<td data-label="Title">' . $contact->title . '</td>';
        $out .= '<td data-label="Notes">' . preg_replace('/[\n\r]/', ' ', $contact->notes) . '</td>';
        if (current_user_can('edit_published_contacts')) {
          $out .= '<td align="center" data-label="Edit"><a href="' . get_edit_post_link($contact->ID) . '" target="_blank">edit</a></td>';
        }
        $out .= '</tr>';
      }
      $out .= '</tbody>';
    }
    echo $out;
    ?>
  </table>
  <script>
    jQuery(document).ready(function($) {

      function updateContactHeaders() {
        $("tbody[data-contact_type_id]").each(function() {
          const id = $(this).data("contact_type_id");

          // Find matching thead
          const $thead = $(`thead[data-contact_type_id="${id}"]`);

          // Find rows that are NOT hidden by CSS
          const visibleRows = $(this).find("tr").filter(function() {
            return (
              $(this).css("display") !== "none" &&
              $(this).css("visibility") !== "hidden" &&
              !$(this).prop("hidden")
            );
          });

          // Hide header if none are visible
          if (visibleRows.length === 0) {
            $thead.find('tr').hide();
          } else {
            $thead.find('tr').show();
          }
        });
      }

      window.search_cps = function search_cps() {
        $el = $('#cps-search-terms');

        var search_terms = $el.val().toLowerCase();

        $('.stripes.contacts-table tr').each(function() {
          var $tr = $(this);
          var region = $(this).data('contact_type_id');
          var show_region = false;

          $tr.hide();

          $checkbox = $('.contact-table-toggle[data-contact_type_id=' + region + ']');
          if ($checkbox.length && $checkbox.is(':checked')) {
            show_region = true;
          }

          $tr.find('td.searchable').each(function() {
            var text = $(this).text().toLowerCase();

            if (text.indexOf(search_terms) != -1 && show_region) {
              $tr.show();
              return false;
            } else {
              $tr.hide();
            }
          });

          // Run once on page load
          updateContactHeaders();
        });
      }

      $('#cps-search-terms').focus();

      $(document).on('keyup', '#cps-search-terms', function() {
        search_cps();
      });

      $(document).on('click', '.contact-table-toggle', function() {
        search_cps();
      });

      //init
      search_cps();
    });
  </script>
<?php endif; ?>
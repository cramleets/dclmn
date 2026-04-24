<?php
global $dclmn;
$dclmn_user = dclmn_get_user();
?>
<?php if (is_object($dclmn_user) && dclmn_user_is_exec()): ?>
  <?php get_template_part('partials/cp-nav'); ?>
  <p>When DCLMN sends out invites to Zoom meetings the following people get included.</p>
  <div class="contact-table-toggles">
    <input id="cps-search-terms" placeholder="Search">
    <span class="cp-search-terms">
      <?php foreach (get_terms(['taxonomy' => 'contact_type', 'hide_empty' => true]) as $term): ?>
        <label><input type="checkbox" class="contact-table-toggle" data-contact_type_id="<?php echo $term->term_id ?>" <?php if (0 && 'Active Dem' == $term->name) echo ' checked'; ?>> <?php echo $term->name ?></label>
      <?php endforeach; ?>
    </span>
    <?php if (current_user_can('edit_published_contacts')): ?>
      <span class="cp-table-buttons">
        <span> | </span>
        <a href="mailto:" class="button email-checked" target="_blank">Email Checked</a>
        <span> | </span>
        <a href="#" class="button copy-checked" target="_blank">Copy Checked to Clipboard</a>
      </span>
    <?php endif; ?>
  </div>
  <?php
  if (current_user_can('edit_published_contacts')) {
    get_template_part('partials/modal-email-to-bcc');
  }
  ?>
  <div class="contact-type-click-note">Check The Contact Types Checkboxes Above To See The Associated Names</div>
  <table cellpadding="5" cellspacing="0" class="stripes contacts-table" border="1">
    <?php
    $out = '';
    foreach (get_terms(['taxonomy' => 'contact_type', 'hide_empty' => true]) as $term) {
      $out .= '<thead data-contact_type_id="' . $term->term_id . '">';
      $out .= '<tr data-contact_type_id="' . $term->term_id . '">';
      if (current_user_can('edit_published_contacts')) {
        $out .= '<td data-label="Email All" style="text-align: center;"><input type="checkbox" class="email-checkbox-all" style="vertical-align: middle;"></td>';
      }
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
        if (current_user_can('edit_published_contacts')) {
          $out .= '<td data-label="Send Email" style="text-align: center;"><input type="checkbox" class="email-checkbox" data-post_id="" ' . $contact->ID . '"></td>';
        }
        $out .= '<td data-label="Contact Type" nowrap>' . $term->name . '</td>';
        $out .= '<td data-label="First Name" class="searchable">' . $contact->first_name . '</td>';
        $out .= '<td data-label="Last Name" class="searchable">' . $contact->last_name . '</td>';
        if (current_user_can('edit_published_contacts')) {
          $out .= '<td data-label="Email" class="searchable"><a href="mailto:' . $contact->email . '" target="_blank">' . $contact->email . '</a></td>';
          $out .= '<td data-label="Phone" class="searchable"><a href="tel:' . $contact->phone . '" target="_blank">' . $contact->phone . '</a></td>';
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
  <div class="contact-table-toggles">
    <?php if (current_user_can('edit_published_contacts')): ?>
      <span class="cp-table-buttons">
        <a href="mailto:" class="button email-checked" target="_blank">Email Checked</a>
        <span> | </span>
        <a href="#" class="button copy-checked" target="_blank">Copy Checked to Clipboard</a>
      </span>
    <?php endif; ?>
  </div>
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
        var show_any_region = false;


        $('.contact-type-click-note').hide();

        $('.stripes.contacts-table tr').each(function() {
          var $tr = $(this);
          var region = $(this).data('contact_type_id');
          var show_region = false;

          $tr.hide();

          $checkbox = $('.contact-table-toggle[data-contact_type_id=' + region + ']');
          if ($checkbox.length && $checkbox.is(':checked')) {
            show_region = true;
            show_any_region = true;
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

          // Run once on page load
          updateContactHeaders();
        });

        if (!show_any_region) {
          $('.contact-type-click-note').show();
        }
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
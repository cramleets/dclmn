<?php
global $dclmn;
$dclmn_user = dclmn_get_user();
$leadership = $dclmn->get_leadership();
?>
<?php if (is_object($dclmn_user) && $dclmn_user->is_exec()): ?>
  <?php get_template_part('partials/cp-nav'); ?>
  <div class="contact-table-toggles">
    <input id="cps-search-terms" placeholder="Search">
    <span class="cp-table-buttons">
      <span> | </span>
      <a href="mailto:" class="button email-checked" target="_blank">Email Checked</a>
      <span> | </span>
      <a href="#" class="button copy-checked" target="_blank">Copy Checked to Clipboard</a>
      <span> | </span>
      <a href="<?php echo admin_url('admin-ajax.php?action=export_leadership') ?>" class="button" target="_blank">Export</a>
    </span>
  </div>
  <?php get_template_part('partials/modal-email-to-bcc'); ?><table cellpadding="5" cellspacing="0" class="stripes leadership">
    <thead>
      <tr>
        <td data-label="Email All" style="text-align: center;"><input type="checkbox" class="email-checkbox-all" style="vertical-align: middle;"></td>
        <td>Office</td>
        <td>Official</td>
        <td>Email</td>
        <td>Phone</td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($leadership as $l): ?>
        <tr valign="top">
          <?php if (!empty($l->email)): ?>
            <td data-label="Send Email" style="text-align: center;"><input type="checkbox" class="email-checkbox" data-post_id="<?php echo $l->ID ?>"></td>
          <?php else: ?>
            <td>&nbsp;</td>
          <?php endif; ?>
          <td class="position"><?php echo str_replace(' (', '<br><small>(', $l->post_title . '</small>') ?></td>
          <td nowrap>
            <?php
            if ($l->email) echo '<a href="mailto:' . $l->email . '" target="_blank">';
            echo $l->first_name . ' ' . $l->last_name;
            if ($l->email) echo '</a>';
            ?>
          </td>
          <td nowrap data-label="Email"><a href="mailto:<?php echo $l->email ?>" target="_blank"><?php echo $l->email ?></td>
          <td nowrap><?php echo $dclmn->get_phone_link($l->phone); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div class="contact-table-toggles">
    <?php if (current_user_can('edit_published_contacts')): ?>
      <a href="mailto:" class="button email-checked" target="_blank">Email Checked</a>
      | <a href="#" class="button copy-checked" target="_blank">Copy Checked to Clipboard</a>
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
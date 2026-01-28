<?php
global $dclmn;
$dclmn_user = dclmn_get_user();
?>
<?php if (is_object($dclmn_user) && $dclmn_user->is_exec()): ?>
  <p class="dashboard-button"><a href="<?php echo home_url('cp/') ?>" class="button">Back to Dashboard</a></p>
  <div class="contact-table-toggles">
    <?php foreach (get_terms(['taxonomy' => 'contact_type', 'hide_empty' => true]) as $term): ?>
      <label><input type="checkbox" class="contact-table-toggle" data-contact_type_id="<?php echo $term->term_id ?>"> <?php echo $term->name ?></label>
    <?php endforeach; ?>
  </div>
  <table cellpadding="5" cellspacing="0" class="stripes contacts-table" border="1">
    <?php
    $out = '';
    foreach (get_terms(['taxonomy' => 'contact_type', 'hide_empty' => true]) as $term) {
      $out .= '<thead>';
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
      foreach ($dclmn->get_contacts($term->term_id) as $contact) {
        $out .= '<tr data-contact_type_id="' . $term->term_id . '">';
        $out .= '<td data-label="Contact Type" nowrap>' . $term->name . '</td>';
        $out .= '<td data-label="First Name">' . $contact->first_name . '</td>';
        $out .= '<td data-label="Last Name">' . $contact->last_name . '</td>';
        if (current_user_can('edit_published_contacts')) {
          $out .= '<td data-label="Email"><a href="mailto:' . $contact->email . '" target="_blank">' . $contact->email . '</a></td>';
          $out .= '<td data-label="Phone">' . $contact->phone . '</td>';
        }
        $out .= '<td data-label="Title">' . $contact->title . '</td>';
        $out .= '<td data-label="Notes">' . preg_replace('/[\n\r]/', ' ', $contact->notes) . '</td>';
        if (current_user_can('edit_published_contacts')) {
          $out .= '<td align="center" data-label="Edit"><a href="' . get_edit_post_link($contact->ID) . '" target="_blank">edit</a></td>';
        }
        $out .= '</tr>';
      }
    }
    echo $out;
    ?>
  </table>
<?php endif; ?>
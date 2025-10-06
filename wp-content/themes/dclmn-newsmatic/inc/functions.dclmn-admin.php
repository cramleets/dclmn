<?php

function dclmn_format_phone_ap($phone) {
  // Keep digits only
  $digits = preg_replace('/\D+/', '', $phone);

  // If 10 digits, format as nnn-nnn-nnnn
  if (strlen($digits) === 10) {
    return substr($digits, 0, 3) . '-' . substr($digits, 3, 3) . '-' . substr($digits, 6);
  }

  // If 11 digits starting with 1 (U.S. country code)
  if (strlen($digits) === 11 && $digits[0] === '1') {
    return substr($digits, 1, 3) . '-' . substr($digits, 4, 3) . '-' . substr($digits, 7);
  }

  // Otherwise, return original (best-effort)
  return $phone;
}

function dclmn_import_committee_people_from_csv($csv_file, $run = false) {
  global $wpdb;

  $results = [
    'success'  => [],
    'failures' => [],
  ];

  if (($handle = fopen($csv_file, 'r')) !== false) {
    // Read header
    $headers = fgetcsv($handle);

    while (($row = fgetcsv($handle)) !== false) {
      $data = array_combine($headers, $row);

      $email = trim($data['Email'] ?? '');
      $phone = trim($data['Phone'] ?? '');

      if (empty($email)) {
        $results['failures'][] = $data;
        continue;
      }

      // Find post by email in postmeta
      $post_id = $wpdb->get_var($wpdb->prepare(
        "SELECT post_id FROM $wpdb->postmeta pm
				 JOIN $wpdb->posts p ON p.ID = pm.post_id
				 WHERE pm.meta_key = 'public_email'
				   AND pm.meta_value = %s
				   AND p.post_type = 'committee_person'
				 LIMIT 1",
        $email
      ));

      if ($post_id) {
        $phone = dclmn_format_phone_ap($phone);

        // Update phone meta
        if ($run) {
          update_post_meta($post_id, 'phone', $phone);
        }

        $results['success'][] = [
          'post_id' => $post_id,
          'email'   => $email,
          'phone'   => $phone,
        ];
      } else {
        $results['failures'][] = $data;
      }
    }

    fclose($handle);
  }

  return $results;
}

function dclmn_committee_people_import_page() {
  $results = [];

  $run = (!empty($_POST['run'])) ? true : false;

  // Handle upload
  if (! empty($_FILES['csv_file']['tmp_name'])) {
    $uploaded = wp_handle_upload($_FILES['csv_file'], ['test_form' => false]);

    if (empty($uploaded['error']) && ! empty($uploaded['file'])) {
      $csv_file = $uploaded['file'];
      $results  = dclmn_import_committee_people_from_csv($csv_file, $run);
    } else {
      echo '<div class="error"><p>Upload failed: ' . esc_html($uploaded['error']) . '</p></div>';
    }
  }

  // Show results if we have them
  if (! empty($results)) {
    echo '<div class="import-results">';
    echo '<h2>Import Results</h2>';

    echo '<h3>RUN: ' . (($run) ? 'YES' : 'NO') . '</h3>';

    echo '<h3>Failures</h3><pre>';
    print_r($results['failures']);
    echo '</pre>';

    echo '<h3>Success</h3><pre>';
    print_r($results['success']);
    echo '</pre>';
    echo '</div>';
  }

  // If no results yet, show upload form
  if (empty($results)) {
?>
    <fieldset>
      <h2>Import Street Lists Committee People CSV</h2>
      <form method="post" enctype="multipart/form-data">
        <p><input type="file" name="csv_file" accept=".csv" required></p>
        <p><label><input type="checkbox" name="run" value="1"> Run</label></p>
        <p><?php submit_button('Upload & Import'); ?></p>
      </form>
    </fieldset>
<?php
  }
}

<?php
/*
Plugin Name: Simple Redirects
Description: Minimal 302 redirects editable by editors.
*/

if (! defined('ABSPATH')) exit;

/**
 * Register CPT
 */
add_action('init', function () {

  register_post_type('simple_redirect', array(
    'labels' => array(
      'name' => 'Redirects',
      'singular_name' => 'Redirect',
    ),
    'public' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'capability_type' => 'post',
    'map_meta_cap' => true,
    'supports' => ['title'],
  ));
});

/**
 * Add meta box
 */
add_action('add_meta_boxes', function () {

  add_meta_box(
    'simple_redirect_fields',
    'Redirect',
    function ($post) {

      $from = get_post_meta($post->ID, '_redirect_from', true);
      $to   = get_post_meta($post->ID, '_redirect_to', true);

      wp_nonce_field('simple_redirect_save', 'simple_redirect_nonce');
?>

    <p>
      <label><strong>From (path only)</strong></label><br>
      <input type="text" name="redirect_from" value="<?php echo esc_attr($from); ?>" style="width:100%;" placeholder="/old-path">
    </p>

    <p>
      <label><strong>To (path or full URL)</strong></label><br>
      <input type="text" name="redirect_to" value="<?php echo esc_attr($to); ?>" style="width:100%;" placeholder="/new-path">
    </p>

<?php
    },
    'simple_redirect',
    'normal',
    'high'
  );
});

/**
 * Save meta
 */
add_action('save_post_simple_redirect', function ($post_id) {

  if (! isset($_POST['simple_redirect_nonce'])) return;
  if (! wp_verify_nonce($_POST['simple_redirect_nonce'], 'simple_redirect_save')) return;

  if (isset($_POST['redirect_from'])) {
    update_post_meta($post_id, '_redirect_from', trim($_POST['redirect_from']));
  }

  if (isset($_POST['redirect_to'])) {
    update_post_meta($post_id, '_redirect_to', trim($_POST['redirect_to']));
  }
});

/**
 * Handle redirects
 */
add_action('template_redirect', function () {

  $path = untrailingslashit(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

  $redirects = get_posts(array(
    'post_type' => 'simple_redirect',
    'post_status' => 'publish',
    'numberposts' => -1,
  ));

  foreach ($redirects as $redirect) {

    $from = untrailingslashit(get_post_meta($redirect->ID, '_redirect_from', true));
    $to   = get_post_meta($redirect->ID, '_redirect_to', true);

    if ($from === $path && $to) {
      wp_redirect($to, 302);
      exit;
    }
  }
});

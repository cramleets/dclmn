<?php

if (! function_exists('tm_theme_ensure_tag_manager_role_caps')) {
  function tm_theme_ensure_tag_manager_role_caps() {
    $role_slug = 'event_manager';
    $role_name = __('Event Manager');

    // Try creating the role (null if it already exists), else fetch it.
    $role = add_role($role_slug, $role_name);
    if (! $role) {
      $role = get_role($role_slug);
    }
    if (! $role) {
      return; // bail if something's off
    }

    // Baseline caps: full control over post tags + common UI fallback
    $baseline_caps = [
      'read'                 => true,

      // Explicit post_tag taxonomy caps (works even if taxonomy is customized)
      'assign_post_tags'     => true,
      'edit_post_tags'       => true,
      'delete_post_tags'     => true,
      'manage_post_tags'     => true,
    ];

    foreach ($baseline_caps as $cap => $grant) {
      if ($grant) {
        $role->add_cap($cap);
      } else {
        $role->remove_cap($cap);
      }
    }

    // Copy any existing capability that contains "tribe" (case-insensitive)
    $wp_roles = wp_roles();
    if ($wp_roles && ! empty($wp_roles->roles)) {
      foreach ($wp_roles->roles as $role_key => $role_data) {
        if (empty($role_data['capabilities']) || ! is_array($role_data['capabilities'])) {
          continue;
        }
        foreach ($role_data['capabilities'] as $cap => $granted) {
          if ($granted && stripos($cap, 'tribe') !== false) {
            $role->add_cap($cap);
          }
        }
      }
    }
  }
}

/**
 * Run once when the theme is activated.
 */
add_action('after_switch_theme', 'tm_theme_ensure_tag_manager_role_caps');

<?php

class DCLMN_Private_Events {
  function __construct() {
    add_action('pre_get_posts', [$this, 'pre_get_posts_private_events'], 100);
    add_filter('tribe_repository_events_query_args', [$this, 'tribe_repository_events_query_args_private_events'], 100);
  }

  function napco_can_see_private_events() {
    return dclmn_auth('exec');
  }

  function napco_private_event_tax_query($tax_query = []) {
    $tax_query = (array) $tax_query;

    $tax_query[] = [
      'taxonomy' => 'tribe_events_cat',
      'field'    => 'slug',
      'terms'    => ['private'],
      'operator' => 'NOT IN',
    ];

    return $tax_query;
  }

  function pre_get_posts_private_events($query) {
    if (is_admin() || $this->napco_can_see_private_events()) {
      return;
    }

    $post_types = (array) $query->get('post_type');

    $is_event_query =
      in_array('tribe_events', $post_types, true)
      || $query->get('tribe_events')
      || $query->get('eventDisplay')
      || $query->get('tribe_events_cat');

    if (!$is_event_query) {
      return;
    }

    $query->set(
      'tax_query',
      $this->napco_private_event_tax_query($query->get('tax_query'))
    );
  }


  function tribe_repository_events_query_args_private_events($args) {
    if ($this->napco_can_see_private_events()) {
      return $args;
    }

    $args['tax_query'] = $this->napco_private_event_tax_query(
      $args['tax_query'] ?? []
    );

    return $args;
  }
}

<?php

class DCLMN_Poll extends DCLMN_Post {

  var $choices;
  var $randomize_choices;
  var $poll_closed = false;

  function __construct( $id, $thumb_size = false ) {
    parent::__construct( $id, $thumb_size );
    $this->set_choices();
  }

  function set_choices() {
    $args = [
        'post_type' => 'poll_choice',
        'posts_per_page' => -1,
        'post_parent' => $this->ID,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ];

    if ( $this->randomize_choices ) {
      $args['orderby'] = 'rand';
    }

    $this->choices = dclmn_get_posts( $args );
  }

  function get_choices() {
    return $this->choices;
  }

  function get_votes( $args=[] ) {
    $defaults = [
        'post_type' => 'poll_vote',
        'posts_per_page' => -1,
        'post_parent' => $this->ID,
        'meta_key' => 'choice_id',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
    ];
    
    $args = wp_parse_args( $args, $defaults );

    return dclmn_get_posts( $args );
  }

}

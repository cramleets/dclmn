<?php

class DCLMN_Post {

  var $ID;
  var $href;
  var $thumb_src;

  function __construct($id, $thumb_size = false) {

    $post = get_post($id);
    if ($post) {

      //set up the global WordPress post info.
      if (!is_admin()) {
        setup_postdata($post->ID);
      }

      //use the $id to get the post meta.
      $meta = get_metadata('post', $post->ID);

      //create an array to store the meta values in and store them.
      $data = array();
      if (is_array($meta) && count($meta)) {
        foreach ($meta as $key => $value) {
          if (property_exists($post, $key))
            continue;
          if (is_array($value) && count($value) > 1) {
            $value = $value;
          } else if ($value) {
            $value = maybe_unserialize($value[0]);
          }
          $post->$key = $value;
        }
      }
      //clean up the global WordPress data. Thank you lord for this function.
      if (!is_admin()) {
        wp_reset_postdata();
      }


      foreach ($post as $key => $value) {
        $this->$key = $value;
      }

      $this->href = get_permalink($this->ID);

      unset($post);
    }
  }
}

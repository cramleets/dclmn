<?php

class Rotating_Quotes_Widget extends WP_Widget {

  public function __construct($widget_id = 'rotating-quotes', $widget_name = 'Rotating Quotes', $widget_ops = array('classname' => 'rotating-quotes'), $control_options = array()) {
    parent::__construct($widget_id, $widget_name, $widget_ops);
  }

  public function widget($args, $instance) {
    $args = [
      'post_type' => 'quote',
      'orderby' => 'rand',
      'posts_per_page' => 1,
    ];
    $posts = dclmn_get_posts($args);

    $out = '';
    if (count($posts)) {
      $post = $posts[0];

      $out .= '<section class="widget rotating-quotes">';
      $out .= '<a href="'. home_url('quotes/') .'">';
      $out .= '<figure class="wp-block-pullquote">';
      $out .= '<blockquote>';
      $out .= '<p>“' . $post->post_content . '”</p>';
      $out .= '<cite>―&nbsp;<strong>' . $post->source . '</strong></cite>';
      $out .= '</blockquote>';
      $out .= '</figure>';
      $out .= '</a>';
      $out .= '</section>';
    }

    echo $out;
  }

  public function update($new_instance, $old_instance) {
    return $new_instance;
  }

  public function form($instance) {
  }
}

register_widget('Rotating_Quotes_Widget');

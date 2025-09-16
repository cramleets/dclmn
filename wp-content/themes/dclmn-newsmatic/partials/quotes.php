<?php

$args = [
  'post_type' => 'quote',
  'orderby' => 'rand',
  'posts_per_page' => -1,
];
$posts = dclmn_get_posts($args);

$out = '';
if (count($posts)) {
  foreach ($posts as $post) {

    $out .= '<figure class="wp-block-pullquote">';
    $out .= '<blockquote>';
    $out .= '<p>“' . $post->post_content . '”</p>';
    $out .= '<cite>―&nbsp;<strong>' . $post->source . '</strong></cite>';
    $out .= '</blockquote>';
    $out .= '</figure>';
  }
}

?>
<div class="quotes-page">
  <div>
    <?php echo $out; ?>
  </div>
  <div class="suggest-a-quote">
    <h2>Suggest a Quote</h2>
    <?php echo do_shortcode('[formidable id=3]') ?>
  </div>
</div>
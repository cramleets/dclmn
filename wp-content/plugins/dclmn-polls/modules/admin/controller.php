<?php
wp_enqueue_script('jquery-ui-sortable');
wp_enqueue_script('jquery-ui-draggable');
wp_enqueue_script('polls-choices', plugin_dir_url(__FILE__) . 'js/meta_box_poll_choices.js');
wp_enqueue_script('polls-results', plugin_dir_url(__FILE__) . 'js/meta_box_poll_results.js');

wp_enqueue_style('polls-choices', plugin_dir_url(__FILE__) . 'css/meta_box_poll_choices.css');
wp_enqueue_style('polls-results', plugin_dir_url(__FILE__) . 'css/meta_box_poll_results.css');

if (!empty($view_file) && 'poll_choices_list' == $view_file) {
    $post = new DCLMN_Poll($_REQUEST['parent_id']);
} else {
    $post = new DCLMN_Poll(get_the_ID());
}

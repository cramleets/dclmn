<?php

function pobj($obj, $exit_flag = 0, $show_footer = 1) {
    echo "<pre>\n";
    print_r($obj);
    echo "\n</pre>\n";

    if ($show_footer) {
        $bt = debug_backtrace();
        echo "[FILE] => " . $bt[0]['file'];
        echo "<br>";
        echo "[LINE] => " . $bt[0]['line'];
    }

    if ($exit_flag)
        exit;
}

function dclmn_get_posts($args) {
    $posts = [];
    foreach (get_posts($args) as $i => $post) {
        foreach (get_post_meta($post->ID) as $k => $v) {
            $post->$k = $v[0];
        }
        $posts[] = $post;
    }
    return $posts;
}

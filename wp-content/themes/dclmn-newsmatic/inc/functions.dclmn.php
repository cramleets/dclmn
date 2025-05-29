<?php

use Newsmatic\CustomizerDefault as ND;

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

add_action('wp_head', function () {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">';
});

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

function newsmatic_bottom_footer_copyright_part() {
    $bottom_footer_site_info = ND\newsmatic_get_customizer_option('bottom_footer_site_info');
?>
    <div class="site-info">
        <?php echo wp_kses_post(str_replace('%year%', date('Y'), $bottom_footer_site_info)); ?>
    </div>
<?php
}

function newsmatic_top_header_html() {
    echo '<div class="newsmatic-container">';
    get_template_part('partials/home-boxes');
    echo '</div>';
}


function newsmatic_header_ads_banner_part_footer() {
    echo '<div class="newsmatic-container">';
    // newsmatic_header_ads_banner_part();
    echo '</div>';
}

/**
 * Override function.
 */
function newsmatic_top_header_social_part() {
}

function newsmatic_header_site_branding_part() {
?>
    <div class="site-branding">
        <?php
        newsmatic_header_ads_banner_part();
        //    the_custom_logo();
        if (is_front_page() && is_home()) :
        ?>
            <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
        <?php
        else :
        ?>
            <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
        <?php
        endif;
        $newsmatic_description = get_bloginfo('description', 'display');
        if ($newsmatic_description || is_customize_preview()) :
        ?>
            <p class="site-description"><?php echo apply_filters('newsmatic_bloginfo_description', esc_html($newsmatic_description)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                        ?></p>
        <?php endif; ?>
    </div><!-- .site-branding -->
<?php
}
add_action('newsmatic_header__site_branding_section_hook', 'newsmatic_header_site_branding_part', 10);

function newsmatic_header_html() {
    require get_template_directory() . '/inc/hooks/header-hooks.php'; // top header hooks.
?>
    <div class="main-header <?php echo esc_attr('order--' . ND\newsmatic_get_customizer_option('main_header_elements_order')); ?>">
        <div class="menu-section">
            <div class="newsmatic-container">
                <div class="row">
                    <?php
                    /**
                     * hook - newsmatic_header__menu_section_hook
                     * 
                     * @hooked - newsmatic_header_menu_part - 10
                     * @hooked - newsmatic_header_search_part - 20
                     */
                    if (has_action('newsmatic_header__menu_section_hook')) do_action('newsmatic_header__menu_section_hook');
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
}

add_action('newsmatic_after_header_html', 'newsmatic_header_ads_banner_part', 10);


function newsmatic_breadcrumb_html() {
}


function import_cps() {
    $rows = array_map('str_getcsv', file('/Users/mps/Desktop/Committee.csv'));
    //echo '<pre>'; print_r($rows);exit;

    $header = array_shift($rows);
    $file = array();
    foreach ($rows as $row) {
        //print_r($header);exit;
        $file[] = array_combine($header, $row);
    }

    // $headers = array_shift($file);

    // echo '<pre>'. print_r($file,1); exit;

    $counters = [];

    foreach ($file as $i => $line) {
        //$line = array_combine($headers, $line);
        //print_r($line);
        // exit;

        $ward_title = array_values($line)[0];
        if (!isset($counters[$ward_title])) $counters[$ward_title] = 0;
        $counters[$ward_title]++;
        $post_title = $ward_title . ' #' . $counters[$ward_title];
        $args = [
            'post_type' => 'committee_person',
            'post_title' => $post_title,
            'post_status' => 'publish',
        ];


        if ($existing_committee_person = get_page_by_path(sanitize_title($post_title), OBJECT, 'committee_person')) {
            $args['ID'] = $existing_committee_person->ID;
            //pobj($existing_committee_person, 1);
            //if ('publish' == $existing_committee_person->post_status) continue;
        }

        //print_r($args); exit;

        $post_id = wp_insert_post($args);

        $name = explode(' ', $line['Name']);

        update_post_meta($post_id, 'public_email', $line['email']);
        update_post_meta($post_id, 'first_name', $name[0]);

        if (!empty($name[1])) {
            update_post_meta($post_id, 'last_name', $name[1]);
        }

        if ($ward = get_page_by_path(sanitize_title($ward_title), OBJECT, 'ward')) {
            update_post_meta($post_id, 'ward', $ward->ID);
        }

        //wp_set_post_terms( int $post_id, string|array $terms = ”, string $taxonomy = ‘post_tag’, bool $append = false ): array|false|WP_Error
        //wp_set_post_terms($post_id, [16], 'jurisdiction');
    }

    die('done');
}

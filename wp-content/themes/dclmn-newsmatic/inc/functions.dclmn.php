<?php

use Newsmatic\CustomizerDefault as ND;
use Tribe__Date_Utils as Dates;

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

function dclmn_get_events($args = []) {
    $defaults = [
        'post_type'      => 'tribe_events',
        'posts_per_page' => 5,
        'eventDisplay'   => 'upcoming',
        'tax_query'      => [],
        'ends_after' => 'now',
    ];

    $args = wp_parse_args($args, $defaults);

    if (!empty($args['category'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'tribe_events_cat',
            'field'    => 'slug',
            'terms'    => (array) $args['category'],
        ];

        unset($args['category']);
    }

    $events = tribe_get_events($args);
    foreach ($events as $i => $event) {
        $events[$i] = tribe_get_event($event->ID);
    }

    return $events;
}

function dclmn_homepage_events($args = []) {
    $events = dclmn_get_events($args);

    $url = home_url('events/');

    if ($args['category']) {
        $url .= 'category/' . $args['category'] . '/list/';
    }

    $out = '';

    $out .= '<div class="dclmn-events">';


    if ($args['header']) {
        $out .=  '<h2><a href="' . $url . '">' . $args['header'] . '</a></h2>';
    }

    foreach ($events as $event) {
        $display_date = empty($is_past) && ! empty($request_date)
            ? max($event->dates->start_display, $request_date)
            : $event->dates->start_display;

        $event_week_day  = $display_date->format_i18n('l');
        $event_week_day_short  = $display_date->format_i18n('D');
        $event_week_day_shorter = substr($event_week_day_short, 0, 2);

        $event_day_num   = $display_date->format_i18n('j');
        $event_month   = $display_date->format_i18n('F');
        $event_month_short   = $display_date->format_i18n('M');
        $event_date_attr = $display_date->format(Dates::DBDATEFORMAT);

        if ($event->multiday) {
            // The date returned back contains HTML and is already escaped.
            $event_time = $event->schedule_details->value();
        } elseif ($event->all_day) {
            $event_time = esc_html_x('All day', 'All day label for event', 'the-events-calendar');
            $event_time_short = 'All Day';
        } else {
            // The date returned back contains HTML and is already escaped.
            $event_time = $event->short_schedule_details->value();
            $event_time_short = $event->dates->start->format_i18n('ga');;
        }
        $out .= '<p class="dclmn-event">';

        $out .= '<span class="date-box">';
        $out .= '<span class="date-box-dow">' . $event_month_short . '</span>';
        $out .= '<span class="date-box-date">' . $event_day_num . '</span>';
        $out .= '<span class="date-box-time">' . $event_time_short . '</span>';
        $out .= '</span>';

        $out .= '<a
		href="' . esc_url($event->permalink) . '"
		title="' . esc_attr($event->title) . '"
		rel="bookmark"
		class="tribe-events-widget-events-list__event-title-link tribe-common-anchor-thin">';

        //$out .= tribe_event_featured_image($event->ID, 'full', false);
        $out .= '<span class="event-title"><strong>' . $event->title . '</strong></span>';
        $out .= '<br>';
        $out .= '<span class="event-week-day">' . $event_week_day . ', </span> ';
        $out .= '<span class="event-month">' . $event_month . '</span> ';
        $out .= '<span class="event-date">' . $event_day_num . '</span> ';
        $out .= '  ';
        $out .= '<span class="event-time">' . $event_time . '</span>';
        $out .= '</a>';
        $out .= '</p>';
    }

    $out .=  '<p><a href="' . $url . '">View More &raquo;</a></p>';

    $out .= '</div>';

    return $out;
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
    get_template_part('partials/mobile-header');
    echo '<div class="newsmatic-container">';
    get_template_part('partials/home-boxes');
    echo '</div>';
}


function newsmatic_header_ads_banner_part_footer() {
    echo '<div class="newsmatic-container">';
    // newsmatic_header_ads_banner_part();
    echo '</div>';
}

function newsmatic_before_inner_content() {
    global $post;
    if (is_object($post) && strstr($post->post_name, '-officials')) {
        echo '<nav class="elected-officials-nav"><h3>Elected Officials</h3>' . do_shortcode('[dclmn-buttons-elected-officials]') . '</nav>';
    }
}

/**
 * Override function.
 */
// function newsmatic_top_header_social_part() {
// }

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

function dclmn_get_nested_menu($menu_name) {
    // Get menu items by name
    $menu_items = wp_get_nav_menu_items($menu_name);
    if (!$menu_items) {
        return [];
    }

    // Recursive function to build the tree
    $build_tree = function ($parent_id = 0) use (&$build_tree, $menu_items) {
        $branch = [];
        foreach ($menu_items as $item) {
            if ((int) $item->menu_item_parent === $parent_id) {
                $children = $build_tree($item->ID);
                if ($children) {
                    $item->children = $children;
                }
                $branch[] = $item;
            }
        }
        return $branch;
    };

    return $build_tree();
}

function dclmn_header_menu($menu_name) {
    $menu = dclmn_get_nested_menu($menu_name);
    $out = '';
    $out .= '<div class="dclmn-header-menu">' . PHP_EOL;
    $out .= '<h4><a href="' . $menu[0]->url . '">' . (($menu[0]->title) ?: $menu[0]->post_title) . '</a></h4>' . PHP_EOL;
    $out .= '<div class="padding">' . PHP_EOL;
    $out .= '<ul>' . PHP_EOL;
    foreach ($menu[0]->children as $menu_item) {
        //$out .= '<li><a href="' . $menu_item->url . '">' . ($menu_item->title) ?: $menu_item->post_title . '</a></li>' .PHP_EOL;
        $out .= '<li><a href="' . $menu_item->url . '">' . (($menu_item->title) ?: $menu_item->post_title) . '</a></li>' . PHP_EOL;
    }
    $out .= '</ul>' . PHP_EOL;
    $out .= '</div>' . PHP_EOL;
    $out .= '</div>' . PHP_EOL;

    return $out;
}


function newsmatic_header_sidebar_toggle_part() {
}
function newsmatic_header_search_part() {
}


function get_recent_posts_and_events() {
    // Get posts
    $posts = get_posts([
        'post_type'      => 'post',
        'posts_per_page' => 10,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [
            [
                'key'     => '_thumbnail_id',
                'compare' => 'EXISTS'
            ]
        ]
    ]);

    // Get upcoming events
    $events = dclmn_get_events([
        'posts_per_page' => 10,
        // 'ends_after' => 'now',
        'tax_query' => array(

            'relation' => 'OR',
            // Condition 1: posts without the excluded term(s)
            array(
                'taxonomy' => 'tribe_events_cat',
                'field'    => 'slug',
                'terms'    => array('featured'), // category to exclude
                'operator' => 'IN',
            ),
            // Condition 2: posts that have no term in this taxonomy
            array(
                'taxonomy' => 'tribe_events_cat',
                'operator' => 'NOT EXISTS',
            ),
        )
    ]);

    // Nested assoc array
    $data = [
        'posts'  => [],
        'events' => []
    ];

    // Fill
    foreach ($posts as $p) {
        $data['posts'][] = $p;
    }

    foreach ($events as $e) {
        $data['events'][] = tribe_get_event($e->ID);
    }

    return $data;
}

function dclmn_thumb($src, $args = array()) {
    $defaults = array(
        'width' => '',
        'height' => '',
        'crop' => '',
        'filters' => '',
        'align' => '',
    );

    $args = wp_parse_args($args, $defaults);
    extract($args);

    //urlencode the src
    $src = preg_replace('#^' . home_url() . '#', '', $src);
    $src = urlencode($src);

    $url = home_url('/thumb.php');
    $url .= '?';

    $url .= 'src=' . $src;
    if (!empty($width))
        $url .= '&w=' . $width;
    if (!empty($height))
        $url .= '&h=' . $height;
    if (!empty($crop))
        $url .= '&c=true';
    if (!empty($filter))
        $url .= '&f=' . $filter;
    if (!empty($align))
        $url .= '&a=' . $align;
    if (!empty($quality))
        $url .= '&q=' . $quality;
    if (!empty($q))
        $url .= '&q=' . $q;

    $url = apply_filters('napco_thumb_src', $url, $args);

    return $url;
}

function dclmn_get_newsletters_from_mailchimp() {

    $url = 'https://us10.campaign-archive.com/feed?u=1bf7e9527f52daea724dcdd02&id=03993f91af';

    $transient_name = 'newsletter_feed';
    if (!$items = get_transient($transient_name)) {
        ini_set('memory_limit', -1);
        $feed = fetch_feed($url);
        $rss_items = $feed->get_items(0, 10);
        $items = [];
        if (! empty($rss_items)) {
            foreach ($rss_items as $rss_item) {
                $items[] = [
                    'title' => $rss_item->get_title(),
                    'date' => $rss_item->get_date('F j, Y'),
                    'permalink' => $rss_item->get_permalink(),
                ];
            }
        }

        $items = base64_encode(serialize($items));
        set_transient($transient_name, $items, 60 * 60);
    }

    $items = unserialize(base64_decode($items));

    return $items;
}

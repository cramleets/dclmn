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

add_action('wp_head', function(){
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

/**
 * 
$blocks = [
    [
        'text'=>'Register To Vote',
        'href'=>'https://www.pavoterservices.pa.gov/Pages/VoterRegistrationApplication.aspx',
    ],
    [
        'text'=>'Get Mail-in Ballot',
        'href'=>'https://www.pa.gov/en/agencies/vote.html',
    ],
    [
        'text'=>'Find Polling Place',
        'href'=>'https://www.pavoterservices.pa.gov/Pages/PollingPlaceInfo.aspx',
    ],
    [
        'text'=>'Check Voter Status',
        'href'=>'https://www.pavoterservices.pa.gov/Pages/VoterRegistrationStatus.aspx',
    ],
];
 */

function newsmatic_top_header_html() {
    echo '<div class="newsmatic-container">';
    dclmn_home_boxes();
    echo '</div>';
}

function dclmn_home_boxes() {
    // if (is_front_page()) :
?>
    <div class="home-boxes">
        <div>
            <a href="<?php echo home_url() ?>"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/dclmn-alt-2.png" style="height: 160px;"></a>
        </div>
        <div>
            <h4><a href="/voting/">Voter Center</a></h4>
            <div class="padding">
                <ul>
                    <li><a href="https://www.pavoterservices.pa.gov/Pages/VoterRegistrationApplication.aspx" target="_blank">Register to Vote</a></li>
                    <li><a href="https://www.pa.gov/en/agencies/vote.html" target="_blank">Get Mail-in Ballot</a></li>
                    <li><a href="https://www.pavoterservices.pa.gov/Pages/VoterRegistrationStatus.aspx" target="_blank">Check Voter Status</a></li>
                    <li><a href="https://www.pavoterservices.pa.gov/Pages/VoterRegistrationStatus.aspx" target="_blank">Find Polling Place</a></li>
                </ul>
            </div>
        </div>
        <div>
            <h4><a href="/get-involved/">Get Involved</a></h4>
            <div class="padding">
                <ul>
                    <li><a href="/get-involved/">Volunteer</a></li>
                    <li><a href="/participate/">Participate</a></li>
                    <li><a href="/committee-people/">Committee People</a></li>
                    <li><a href="https://secure.actblue.com/donate/democratic-committee-of-lower-merion-and-narberth-1" target="_blank">Donate</a></li>
                </ul>
            </div>
        </div>
        <div>
            <h4><a href="/events/">Events</a></h4>
            <div class="padding">
                <ul>
                    <li><a href="/events/category/meetings/">Meetings</a></li>
                    <li><a href="/events/category/campaign-dates/">Campaign Dates</a></li>
                    <li><a href="/events/category/election-dates/">Election Dates</a></li>
                    <li><a href="/events/">More</a></li>
                </ul>
            </div>
        </div>
    </div>
<?php
    // endif;
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
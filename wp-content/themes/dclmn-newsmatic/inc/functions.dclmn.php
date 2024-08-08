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


function newsmatic_header_ads_banner_part() {
    if (!ND\newsmatic_get_multiselect_tab_option('header_ads_banner_responsive_option')) return;
    $header_ads_banner_custom_image = ND\newsmatic_get_customizer_option('header_ads_banner_custom_image');
    $header_ads_banner_custom_url = ND\newsmatic_get_customizer_option('header_ads_banner_custom_url');
    $header_ads_banner_custom_target = ND\newsmatic_get_customizer_option('header_ads_banner_custom_target');
    if (!empty($header_ads_banner_custom_image)) :
    ?>
        <div class="ads-banner">
            <a href="<?php echo esc_url($header_ads_banner_custom_url); ?>" target="<?php echo esc_html($header_ads_banner_custom_target); ?>"><img src="<?php echo esc_url(wp_get_attachment_url($header_ads_banner_custom_image)); ?>"></a>


            <div data-testid="columns" class="V5AUxf"><div id="comp-k6xivv5p" class="comp-k6xivv5p YzqVVZ wixui-column-strip__column"><div id="bgLayers_comp-k6xivv5p" data-hook="bgLayers" data-motion-part="BG_LAYER" class="MW5IWV"><div data-testid="colorUnderlay" class="LWbAav Kv1aVt"></div><div id="bgMedia_comp-k6xivv5p" data-motion-part="BG_MEDIA" class="VgO9Yg"></div></div><div data-mesh-id="comp-k6xivv5pinlineContent" data-testid="inline-content" class=""><div data-mesh-id="comp-k6xivv5pinlineContent-gridContainer" data-testid="mesh-container-content"><div class="comp-k6xivv5u2 Vq4wYb" id="comp-k6xivv5u2" aria-disabled="false"><a data-testid="linkElement" href="https://www.democratslmn.org/copy-of-get-involved" target="_self" class="uUxqWY wixui-button PlZyDq" aria-disabled="false"><span class="wJVzSK wixui-button__label">Get Involved</span></a></div><div id="comp-k6xivv6h" class="HcOXKn SxM0TO QxJLC3 comp-k6xivv6h wixui-rich-text" data-testid="richTextElement"><p class="font_8 wixui-rich-text__text" style="text-align:center; font-size:18px;"><span class="color_11 wixui-rich-text__text">Volunteer, Participate or Donate</span></p></div></div></div></div><div id="comp-k6xivv3k1" class="comp-k6xivv3k1 YzqVVZ wixui-column-strip__column"><div id="bgLayers_comp-k6xivv3k1" data-hook="bgLayers" data-motion-part="BG_LAYER" class="MW5IWV"><div data-testid="colorUnderlay" class="LWbAav Kv1aVt"></div><div id="bgMedia_comp-k6xivv3k1" data-motion-part="BG_MEDIA" class="VgO9Yg"></div></div><div data-mesh-id="comp-k6xivv3k1inlineContent" data-testid="inline-content" class=""><div data-mesh-id="comp-k6xivv3k1inlineContent-gridContainer" data-testid="mesh-container-content"><div class="comp-k6xivv3p4 Vq4wYb" id="comp-k6xivv3p4" aria-disabled="false"><a data-testid="linkElement" href="https://www.democratslmn.org/voting" target="_self" class="uUxqWY wixui-button PlZyDq" aria-disabled="false"><span class="wJVzSK wixui-button__label">Voter Center</span></a></div><div id="comp-k6xivv4f" class="HcOXKn SxM0TO QxJLC3 comp-k6xivv4f wixui-rich-text" data-testid="richTextElement"><p class="font_8 wixui-rich-text__text" style="text-align:center; font-size:18px;"><span class="color_11 wixui-rich-text__text">Registration,&nbsp;Ballots by Mail, Dates</span></p></div></div></div></div><div id="comp-kl2dzd2y" class="comp-kl2dzd2y YzqVVZ wixui-column-strip__column"><div id="bgLayers_comp-kl2dzd2y" data-hook="bgLayers" data-motion-part="BG_LAYER" class="MW5IWV"><div data-testid="colorUnderlay" class="LWbAav Kv1aVt"></div><div id="bgMedia_comp-kl2dzd2y" data-motion-part="BG_MEDIA" class="VgO9Yg"></div></div><div data-mesh-id="comp-kl2dzd2yinlineContent" data-testid="inline-content" class=""><div data-mesh-id="comp-kl2dzd2yinlineContent-gridContainer" data-testid="mesh-container-content"><div class="comp-kl2dzd44 Vq4wYb" id="comp-kl2dzd44" aria-disabled="false"><a data-testid="linkElement" href="https://www.democratslmn.org/events" target="_self" class="uUxqWY wixui-button PlZyDq" aria-disabled="false"><span class="wJVzSK wixui-button__label">Events</span></a></div><div id="comp-kl2dzd4c" class="HcOXKn SxM0TO QxJLC3 comp-kl2dzd4c wixui-rich-text" data-testid="richTextElement"><p class="font_8 wixui-rich-text__text" style="text-align:center; font-size:18px;"><span class="color_11 wixui-rich-text__text">Meetings, Election Dates &amp; More</span></p></div></div></div></div></div>
        </div><!-- .ads-banner -->
<?php
    endif;
}

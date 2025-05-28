<?php


class DCLMN {

    var $committee_people = [];
    var $wards = [];
    var $polling_places = [];
    var $pa_districts = [];
    var $elected_officials = [];
    var $builds = [];

    function __construct() {
        add_action('wp_enqueue_scripts', function () {
            $parent_style = 'dclmn-parent';


            wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css', [], filemtime(get_template_directory() . '/style.css'));
            wp_enqueue_style('dclmn-child', get_stylesheet_directory_uri() . '/style.css', [$parent_style], filemtime(get_stylesheet_directory() . '/style.css'));
        }, 98);

        add_action('init', function () {
            add_action('newsmatic_botttom_footer_hook', 'newsmatic_bottom_footer_copyright_part', 20);
        });

        add_filter('use_block_editor_for_post_type', '__return_false');

        add_filter('document_title_parts', function ($title) {
            if (!$title['title']) $title['title'] = get_bloginfo();
            else $title['title'] = $title['title'] .' | '. get_bloginfo();
            return $title;
        });

        add_action('newsmatic_after_header_hook', 'newsmatic_header_ads_banner_part', 10);
        add_action('newsmatic_main_banner_hook', 'newsmatic_header_ads_banner_part_footer', 999);

        add_shortcode('dclmn-subscribe', [$this, 'subscribe']);
        add_shortcode('dclmn-leadership', [$this, 'leadership']);
        add_shortcode('dclmn-committeepeople', [$this, 'committeepeople']);
        add_shortcode('dclmn-elected-officials', [$this, 'elected_officials']);
        add_shortcode('dclmn-county', [$this, 'county']);
        add_shortcode('dclmn-local', [$this, 'local']);
        add_shortcode('dclmn-map', [$this, 'map']);
    }

    function get_committee_people() {
        if (!count($this->committee_people)) {
            $args = [
                'post_type' => 'committee_person',
                'posts_per_page' => -1,
            ];
            $this->committee_people = dclmn_get_posts($args);
        }

        return $this->committee_people;
    }

    function get_wards() {
        if (!count($this->wards)) {
            $args = [
                'post_type' => 'ward',
                'posts_per_page' => -1,
            ];

            $this->wards = dclmn_get_posts($args);
        }

        return $this->wards;
    }

    function get_polling_places() {
        if (!count($this->polling_places)) {
            $args = [
                'post_type' => 'polling_place',
                'posts_per_page' => -1,
            ];

            $this->polling_places = dclmn_get_posts($args);
        }

        return $this->polling_places;
    }

    function get_pa_districts() {
        if (!count($this->pa_districts)) {
            $args = [
                'post_type' => 'pa_district',
                'posts_per_page' => -1,
            ];

            $this->pa_districts = dclmn_get_posts($args);
        }

        return $this->pa_districts;
    }

    function get_elected_officials() {
        if (!count($this->elected_officials)) {
            $args = [
                'post_type' => 'elected_official',
                'posts_per_page' => -1,
            ];

            $this->elected_officials = dclmn_get_posts($args);
        }

        return $this->elected_officials;
    }

    function build_committee_people() {
        $committee_people = $this->get_committee_people();
        $wards = $this->get_wards();
        $polling_places = $this->get_polling_places();
    }

    function build_committee() {
        $wards = $polling_places = $pa_districts = $polling_places = $elected_officials = $committee_people = [];

        foreach ($this->get_elected_officials() as $post) {
            $elected_officials[$post->ID] = $post;
        }

        foreach ($this->get_polling_places() as $post) {
            $polling_places[$post->ID] = $post;
        }

        foreach ($this->get_pa_districts() as $post) {
            $post->representative = $elected_officials[$post->representative];
            $pa_districts[$post->ID] = $post;
        }

        foreach ($this->get_wards() as $post) {
            $post->committe_people = [];
            $post->pa_district = $pa_districts[$post->pa_district];
            $post->polling_place = $polling_places[$post->polling_place];
            $wards[$post->ID] = $post;
        }

        foreach ($this->get_committee_people() as $post) {
            $post->ward = $wards[$post->ward];
            $committee_people[$post->ID] = $post;


            $wards[$post->ward->ID]->committe_people[] = $post;
        }

        $this->builds['wards'] = $wards;
        $this->builds['committee_people'] = $committee_people;
        $this->builds['elected_officials'] = $elected_officials;
        $this->builds['polling_places'] = $polling_places;
        $this->builds['pa_districts'] = $pa_districts;
    }

    function get_committee_people_table() {
        $this->build_committee();

        $wards = [];
        foreach ($this->builds['wards'] as $ward) {
            $wards[$ward->post_title] = $ward;
        }
        ksort($wards);

        $out = '';
        $out .= '<table cellpadding="5" cellspacing="0" class="stripes">';
        $out .= '<thead>';
        $out .= '<tr valign="top">';
        $out .= '<td>Ward</td>';
        $out .= '<td>PA District</td>';
        $out .= '<td>Name</td>';
        $out .= '<td>Polling Place<td>';
        $out .= '</tr>';
        $out .= '</thead>';
        $out .= '<tbody>';
        foreach ($wards as $ward) {
            $district = str_replace('th district', '', strtolower($ward->pa_district->post_title));

            $out .= '<tr valign="top">';
            $out .= '<td>' . $ward->post_title . '</td>';
            $out .= '<td><a href="' . $ward->pa_district->website . '" target="_blank">' . $district . '</a></td>';
            $out .= '<td>';
            foreach ($ward->committe_people as $person) {
                $out .= '<a href="mailto:' . $person->public_email . '" target="_blank">' . $person->first_name . ' ' . $person->last_name . '</a><br>';
            }
            $out .= '</td>';
            $out .= '<td><a href="' . $ward->polling_place->map_url . '" target="_blank">' . $ward->polling_place->post_title . '</a></td>';
            $out .= '</tr>';
        }
        $out .= '</tbody>';
        $out .= '</table>';

        return $out;
    }

    function get_jurisdictions($args = []) {
        $jurisdictions = [];

        foreach (get_terms('jurisdiction', $args) as $jurisdiction) {
            $args = array(
                'post_type' => 'elected_official',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => $jurisdiction->taxonomy,
                        'field' => 'term_id',
                        'terms' => $jurisdiction->term_id
                    )
                )
            );
            $posts = dclmn_get_posts($args);

            $jurisdictions[] = [
                'term' => $jurisdiction,
                'posts' => $posts,
            ];
        }

        return $jurisdictions;
    }


    public function thumb($src, $args = array()) {
        /**
         * Adjust for old calls. 
         */
        if (is_array($src)) {
            $args = $src;
            $src = $args['src'];
            unset($args['src']);
        }

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

        $url = home_url('thumb.php');
        $url .= '?';

        $url .= 'src=' . $src;
        $url .= '&w=';
        $url .= (isset($width)) ? $width : '0';
        $url .= '&h=';
        $url .= (isset($height)) ? $height : '0';

        if (isset($crop))
            $url .= '&c=true';
        if (isset($filter))
            $url .= '&f=' . $filter;
        if (isset($align))
            $url .= '&a=' . $align;
        if (isset($quality))
            $url .= '&q=' . $quality;
        if (isset($q))
            $url .= '&q=' . $q;

        return $url;
    }

    function get_elected_officials_table($args = []) {
        $jurisdictions = $this->get_jurisdictions($args);

        $out = '';

        foreach ($jurisdictions as $jurisdiction) {
            $out .= '<h2>' . $jurisdiction['term']->name . '</h2>';
            $out .= '<div class="officials-table">';
            foreach ($jurisdiction['posts'] as $post) {
                $out .= '<div>';
                $default_src = (!empty($post->gender)) ? 'silouhette-' . $post->gender . '.png' : 'silouhette-male.png';
                $src = (get_the_post_thumbnail_url($post->ID, 'medium')) ?: get_stylesheet_directory_uri() . '/images/' . $default_src;
                $out .= ($src) ? '<img src="' . $this->thumb($src, ['width' => 250, 'height' => 250]) . '">' : '';
                $out .= '<strong style="font-size: 1.25em;">' . (($post->title) ? $post->title : $post->post_title) . '</strong><br>';
                $out .= '<strong>' . $post->first_name . ' ' . $post->last_name . '</strong><br>';
                $out .= '</div>';
            }
            $out .= '</div>';
        }


        return $out;
    }

    function get_leadership() {
        $args = [
            'post_type' => 'committee-position',
            'posts_per_page' => -1,
            'order' => 'ASC'
        ];

        return dclmn_get_posts($args);
    }

    function subscribe() {
        get_template_part('subscribe-form');
    }

    function committeepeople() {
        return $this->get_committee_people_table();
    }

    function elected_officials() {
        get_template_part('elected-officials');
    }

    function county() {
        get_template_part('county');
    }

    function local() {
        get_template_part('local');
    }

    function leadership() {
        get_template_part('leadership');
    }

    function map() {
        get_template_part('map');
    }
}

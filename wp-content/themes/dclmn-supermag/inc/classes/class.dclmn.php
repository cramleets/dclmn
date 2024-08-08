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
            $parent_style = 'dupermag-parent-style';
            wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
        }, 98);

        add_filter('use_block_editor_for_post_type', '__return_false'); {
        }
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
        $out .= '<table border cellpadding="5" cellspacing="0">';
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
        $out .= '</table>';

        return $out;
    }

    function get_jurisdictions() {
        $jurisdictions = [];

        foreach (get_terms('jurisdiction') as $jurisdiction) {
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

    function get_elected_officials_table() {
        $jurisdictions = $this->get_jurisdictions();

        $out = '';

        foreach($jurisdictions as $jurisdiction) {
            $out .= '<h3>'. $jurisdiction['term']->name .'</h3>';
            foreach($jurisdiction['posts'] as $post) {
                $out .= $post->post_title .'<br>';
                $out .= $post->first_name .' '. $post->last_name .'<br>';
                $out .= '<br>';
            }
        }


        return $out;
    }
}

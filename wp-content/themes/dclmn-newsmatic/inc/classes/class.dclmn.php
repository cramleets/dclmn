<?php


class DCLMN {

    var $committee_people = [];
    var $wards = [];
    var $polling_places = [];
    var $pa_districts = [];
    var $elected_officials = [];
    var $builds = [];

    function __construct() {
        if (strpos($_SERVER['HTTP_HOST'], 'dclmn.us') === false) {
            wp_redirect('https://dclmn.us' . $_SERVER['REQUEST_URI'], 301);
            exit;
        }

        add_action('wp_enqueue_scripts', function () {
            $parent_style = 'dclmn-parent';

            wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css', [], filemtime(get_template_directory() . '/style.css'));
            wp_enqueue_style('dclmn-child', get_stylesheet_directory_uri() . '/css/dclmn-main.css', [$parent_style], filemtime(get_stylesheet_directory() . '/css/dclmn-main.css'));
            wp_enqueue_style('dclmn-responsive', get_stylesheet_directory_uri() . '/css/dclmn-responsive.css', ['dclmn-child'], filemtime(get_stylesheet_directory() . '/css/dclmn-responsive.css'));

            wp_enqueue_script('dclmn', get_stylesheet_directory_uri() . '/js/dclmn.js', ['jquery'], filemtime(get_stylesheet_directory() . '/js/dclmn.js'));
        }, 98);

        add_action('init', function () {
            add_action('newsmatic_botttom_footer_hook', 'newsmatic_bottom_footer_copyright_part', 20);
        });

        add_action('init', function () {
            add_action('newsmatic_before_inner_content', 'newsmatic_before_inner_content', 20);
        });

        add_action('wp_head', function () {
            get_template_part('partials/google-analytics');
        });

        add_filter('use_block_editor_for_post_type', '__return_false');

        add_filter('document_title_parts', function ($title) {
            if (!$title['title']) $title['title'] = get_bloginfo();
            else $title['title'] = $title['title'] . ' | ' . get_bloginfo();
            return $title;
        });

        add_action('newsmatic_after_header_hook', 'newsmatic_header_ads_banner_part', 10);
        add_action('newsmatic_main_banner_hook', 'newsmatic_header_ads_banner_part_footer', 999);

        $path = trailingslashit(get_stylesheet_directory()) . 'partials';
        foreach (new DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot()) continue;
            $key = $fileInfo->getBasename('.' . $fileInfo->getExtension());
            add_shortcode('dclmn-' . $key, function ($atts, $content = null, $tag = '') use ($key) {
                global $my_shortcode_context;

                $my_shortcode_context = [
                    'tag'     => $tag,
                    'atts'    => $atts,     // ← raw, unfiltered
                    'content' => $content,
                ];

                ob_start();
                get_template_part("partials/{$key}");
                $out = ob_get_clean();
                ob_end_flush();
                return $out;
            });
        }

        add_filter('tec_events_calendar_embeds_post_type_args', function ($args) {
            // Tell WP to build caps off "tribe_event" instead of "post"
            $args['capability_type'] = ['tribe_event', 'tribe_events']; // singular, plural
            // $args['map_meta_cap']    = true;

            // If the plugin set any explicit caps, wipe them so the mapping applies
            unset($args['capabilities']);

            return $args;
        });

        add_action('wp_ajax_export_cps', [$this, 'wp_ajax_export_cps']);
        add_action('wp_ajax_export_leadership', [$this, 'wp_ajax_export_leadership']);

        add_filter('tec_events_views_v2_view_header_title', function ($title, $obj) {
            if (empty($title)) $title = 'Events';
            else $title = 'Events &raquo; ' . $title;
            return $title;
        }, 10, 2);

        add_filter('newsmatic_query_args_filter', function ($args) {
        });

        add_filter('tribe_widget_events-list_args_to_context', function ($args) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'tribe_events_cat',
                    'terms' => 'meeting',
                    'field' => 'slug',
                    'operator' => 'IN'
                )
            );

            return $args;
        });

        add_filter(
            'wp_mail_from',
            function ($old) {
                return 'web@dclmn.com';
            }
        );

        add_filter(
            'wp_mail_from_name',
            function ($old) {
                return html_entity_decode(get_bloginfo());
            }
        );

        add_filter('body_class', function ($classes) {
            global $post;
            $post_name = 'default';
            if (is_front_page()) $post_name = 'home';
            elseif (is_404()) $post_name = '404';
            elseif ($post->post_name) $post_name = $post->post_name;
            $post_name = apply_filters('napco_page_name', $post_name);
            $classes[] = $post_name;
            return $classes;
        });

        add_filter('tribe_event_featured_image', function ($featured_image, $post_id, $size) {
            if ($url = tribe_get_event_website_url($post_id)) {
                $featured_image = '<a href="' . tribe_get_event_website_url($post_id) . '" target="_blank">' . $featured_image . '</a>';
            }

            return $featured_image;
        }, 10, 3);

        add_action('admin_menu', function () {
            add_submenu_page(
                'tools.php',
                'DCLMN Tools',
                'DCLMN Tools',
                'update_core',
                'dclmn-tools',
                [$this, 'dclmn_tools']
            );
        });
    }

    function get_leadership() {
        $args = [
            'post_type' => 'committee-position',
            'posts_per_page' => -1,
            'order' => 'ASC'
        ];

        return dclmn_get_posts($args);
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
        if (!empty($this->builds)) return;

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

    function format_phone($str) {
        $num = preg_replace('/\D/', '', $str);
        return '' . substr($num, 0, 3) . '-' . substr($num, 3, 3) . '-' . substr($num, 6);
    }

    function get_phone_link($str) {
        if (!empty($str)) {
            $phone = $this->format_phone($str);
            return '<a href="tel:' . $phone . '">' . $phone . '</a>';
        }
    }

    function get_committee_people_table($array = false) {
        $this->build_committee();

        $wards = [];
        foreach ($this->builds['wards'] as $ward) {
            $wards[$ward->post_title] = $ward;
        }

        uksort($wards, function ($a, $b) {
            [$a1, $a2] = explode('-', $a);
            [$b1, $b2] = explode('-', $b);

            // Prioritize 'N' keys first
            if ($a1 === 'N' && $b1 !== 'N') return -1;
            if ($a1 !== 'N' && $b1 === 'N') return 1;

            // Both are 'N' — compare second part numerically
            if ($a1 === 'N' && $b1 === 'N') {
                return (int)$a2 <=> (int)$b2;
            }

            // Otherwise, compare both numerically
            return [(int)$a1, (int)$a2] <=> [(int)$b1, (int)$b2];
        });

        $out = '';
        if ($array) {
            $cps = [];

            $headers = [
                "Ward",
                "PA District",
                "Ward",
                "First Name",
                "Last Name",
                "Email",
                "Phone",
                "Phone is Public",
                "Polling Place",
                "Polling Place Map"
            ];

            $cps[] = $headers;

            foreach ($wards as $ward) {
                foreach ($ward->committe_people as $person) {
                    $district = str_replace('th district', '', strtolower($ward->pa_district->post_title));

                    $cps[] = [
                        $ward->post_title,
                        $district,
                        $ward->post_title,
                        $person->first_name,
                        $person->last_name,
                        $person->public_email,
                        $person->phone,
                        ($person->phone_display_on_site) ? 1 : '',
                        $ward->polling_place->post_title,
                        $ward->polling_place->map_url
                    ];
                }
            }

            return $cps;
        } else {
            $out .= '<table cellpadding="5" cellspacing="0" class="stripes committee-people">';
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
                $out .= '<td data-label="Ward"" class="ward">' . $ward->post_title . '</td>';
                $out .= '<td data-label="District">' . $district . '</td>';
                $out .= '<td data-label="Committee People">';
                foreach ($ward->committe_people as $person) {
                    if ('vacant' == strtolower($person->first_name)) {
                        $out .= $person->first_name;
                        $out .= ' - <a href="' . home_url('committee-person-description/') . '">Inquire</a>';
                    } else {
                        $out .= ($person->public_email) ? '<a href="mailto:' . $person->public_email . '" target="_blank">' : '';
                        $out .= $person->first_name;
                        $out .= ($person->last_name) ? ' ' . $person->last_name : '';
                        $out .= ($person->public_email) ? '</a>' : '';

                        if (current_user_can('edit_others_posts') || (!empty($person->phone) && $person->phone_display_on_site)) {
                            $out .= '<br><small><a href="tel:' . $person->phone . '">' . $person->phone . '</a></small>';
                        }
                    }
                    $out .= '<br>';
                }
                $out .= '</td>';
                $out .= '<td data-label="Polling Place"><a href="' . $ward->polling_place->map_url . '" target="_blank">' . $ward->polling_place->post_title . '</a></td>';
                $out .= '</tr>';
            }
            $out .= '</tbody>';
            $out .= '</table>';
        }
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

        $out .= '<div class="officials-table-wrap ' . sanitize_title($args['search']) . '">';
        foreach ($jurisdictions as $jurisdiction) {
            $out .= '<h2>' . $jurisdiction['term']->name . '</h2>';
            $out .= '<div class="officials-table" style="--count: ' . count($jurisdiction['posts']) . ';">';
            foreach ($jurisdiction['posts'] as $post) {
                $out .= '<div>';
                $default_src = (!empty($post->gender)) ? 'silouhette-' . $post->gender . '.png' : 'silouhette-male.png';
                $src = (get_the_post_thumbnail_url($post->ID, 'medium')) ?: get_stylesheet_directory_uri() . '/images/' . $default_src;
                $url = $post->website_government;
                $out .= ($url) ? '<a href="' . $url . '" target="_blank">' : '';
                $out .= ($src) ? '<img src="' . $this->thumb($src, ['width' => 250, 'height' => 250]) . '">' : '';
                $out .= '<strong style="font-size: 1.25em;">' . (($post->title) ? $post->title : $post->post_title) . '</strong><br>';
                $out .= '<strong>' . $post->first_name . ' ' . $post->last_name . '</strong><br>';
                $out .= ($url) ? '</a>' : '';
                $out .= '</div>';
            }
            $out .= '</div>';
        }
        $out .= '</div>';

        return $out;
    }

    public function downloadFile($content, $filename, $args = []) {
        $filename = ($filename) ?: ($args['filename']) ?: 'export.txt';

        //https://stackoverflow.com/questions/2021624/string-sanitizer-for-filename
        // Remove anything which isn't a word, whitespace, number
        // or any of the following caracters -_~,;[]().
        $filename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $filename);

        // Remove any runs of periods (thanks falstro!)
        $filename = mb_ereg_replace("([\.]{2,})", '', $filename);

        $headers = array(
            'Content-Disposition: attachment; filename="' . $filename,
            'Content-Type: text/plain; charset=UTF-8',
            'Content-Type: application/force-download',
            'Content-Type: application/octet-stream',
            'Content-Type: application/download',
            'Content-Transfer-Encoding: binary',
            'Content-Length: ' . strlen($content),
        );
        foreach ($headers as $header) {
            header($header);
        }
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }

        die($content);
    }

    function wp_ajax_export_cps() {
        require_once trailingslashit(get_stylesheet_directory()) . 'inc/classes/SimpleXLSXGen.php';

        if (!current_user_can('edit_others_posts')) {
            wp_die('Nope.');
        }
        $cps = $this->get_committee_people_table(true);

        Shuchkin\SimpleXLSXGen::fromArray($cps)->saveAs('/tmp/test.xlsx');
        $this->downloadFile(file_get_contents('/tmp/test.xlsx'), 'dclmn-committee-people.xlsx');
    }

    function wp_ajax_export_leadership() {
        require_once trailingslashit(get_stylesheet_directory()) . 'inc/classes/SimpleXLSXGen.php';

        if (!current_user_can('edit_others_posts')) {
            wp_die('Nope.');
        }

        $headers = [
            "Office",
            "First Name",
            "Last Name",
            "Email",
            "Phone",
        ];

        $leadership = [];
        $leadership[] = $headers;

        foreach ($this->get_leadership() as $l) {
            $leadership[] = [
                'title' => $l->post_title,
                'first_name' => $l->first_name,
                'last_name' => $l->last_name,
                'email' => $l->email,
                'phone' => $l->phone,
            ];
        }

        Shuchkin\SimpleXLSXGen::fromArray($leadership)->saveAs('/tmp/test.xlsx');
        $this->downloadFile(file_get_contents('/tmp/test.xlsx'), 'dclmn-leadership.xlsx');
    }


    function get_recent_posts_and_events2() {
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
        $events = get_posts([
            'post_type'      => 'post',
            'posts_per_page' => 10,
            'meta_query'     => [
                [
                    'key'     => '_EventStartDate',
                    'value'   => current_time('Y-m-d H:i:s'),
                    'compare' => '>=',
                    'type'    => 'DATETIME'
                ],
                [
                    'key'     => '_thumbnail_id',
                    'compare' => 'EXISTS'
                ]
            ],
            'tax_query' => [
                'relation' => 'OR',
                [
                    'taxonomy' => 'tribe_events_cat',
                    'field'    => 'slug',
                    'terms'    => ['featured'],
                    'operator' => 'IN',
                ],
                [
                    'taxonomy' => 'tribe_events_cat',
                    'operator' => 'NOT EXISTS',
                ],
            ],
            'orderby'   => 'meta_value',
            'order'     => 'ASC',
            'meta_key'  => '_EventStartDate'
        ]);

        // Nested assoc array
        $data = [
            'posts'  => [],
            'events' => []
        ];

        foreach ($posts as $p) {
            $data['posts'][] = $p;
        }

        foreach ($events as $e) {
            // use tribe_get_event() if you want the enriched object
            // $data['events'][] = function_exists('tribe_get_event') ? tribe_get_event($e) : $e;
        }

        return $data;
    }



    function get_recent_posts_and_events() {
        global $wpdb;

        // ---- Get last 10 posts with thumbnails ----
        $posts_sql = $wpdb->prepare("
        SELECT p.ID, p.post_title, p.post_type, p.post_date AS sort_date
        FROM {$wpdb->posts} p
        WHERE p.post_type = %s
          AND p.post_status = 'publish'
          AND EXISTS (
            SELECT 1 FROM {$wpdb->postmeta} pm
            WHERE pm.post_id = p.ID AND pm.meta_key = '_thumbnail_id'
          )
        ORDER BY p.post_date DESC
        LIMIT 10
    ", 'post');

        $posts = $wpdb->get_results($posts_sql);

        // ---- Get next 10 events with thumbnails and tax filter ----
        $events_sql = "
        SELECT p.ID, p.post_title, p.post_type, pm.meta_value AS sort_date
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm
          ON p.ID = pm.post_id AND pm.meta_key = '_EventStartDate'
        LEFT JOIN {$wpdb->term_relationships} tr
          ON p.ID = tr.object_id
        LEFT JOIN {$wpdb->term_taxonomy} tt
          ON tr.term_taxonomy_id = tt.term_taxonomy_id
        LEFT JOIN {$wpdb->terms} t
          ON tt.term_id = t.term_id
        WHERE p.post_type IN ('tribe_event','tribe_events')
          AND p.post_status = 'publish'
          AND EXISTS (
            SELECT 1 FROM {$wpdb->postmeta} pm2
            WHERE pm2.post_id = p.ID AND pm2.meta_key = '_thumbnail_id'
          )
          AND STR_TO_DATE(pm.meta_value, '%%Y-%%m-%%d %%H:%%i:%%s') >= NOW()
          AND (
                (tt.taxonomy = 'tribe_events_cat' AND t.slug = 'featured')
             OR (tt.taxonomy IS NULL) -- covers NOT EXISTS
          )
        ORDER BY pm.meta_value ASC
        LIMIT 10
    ";

        $events = $wpdb->get_results($events_sql);

        // ---- Build return array ----
        $data = [
            'posts'  => [],
            'events' => []
        ];

        foreach ($posts as $p) {
            $data['posts'][] = $p;
        }

        foreach ($events as $e) {
            if (function_exists('tribe_get_event')) {
                $data['events'][] = tribe_get_event($e->ID); // enriched event object
            } else {
                $data['events'][] = $e; // fallback
            }
        }

        return $data;
    }

    function dclmn_tools() {
        require get_stylesheet_directory() .'/dclmn-tools.php';
    }
}

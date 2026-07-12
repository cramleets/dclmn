<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package eventful
 * @subpackage eventful/Frontend
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\Eventful\Frontend;

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulLiveFilter;
use ThemeAtelier\Eventful\Frontend\Helpers\EventfulQueryInside;
use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;
use ThemeAtelier\Eventful\Frontend\Helpers\EventfulLoopHtml;

/**
 * The Frontend class to manage all public facing stuffs.
 *
 * @since 1.0.0
 */
class Frontend
{
	/**
	 * Script and style suffix
	 *
	 * @since 2.2.0
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct()
	{
		$this->load_public_dependencies();
		$this->eventful_public_action();
	}

	private function load_public_dependencies()
	{
		new EventfulLiveFilter();
	}

	private function eventful_public_action()
	{
		add_action('wp_ajax_event_grid_ajax', array($this, 'event_grid_ajax'));
		add_action('wp_ajax_nopriv_event_grid_ajax', array($this, 'event_grid_ajax'));

		add_action('wp_ajax_event_pagination_bar', array($this, 'event_pagination_bar'));
		add_action('wp_ajax_nopriv_event_pagination_bar', array($this, 'event_pagination_bar'));

		add_action('wp_ajax_event_pagination_bar_mobile', array($this, 'event_pagination_bar_mobile'));
		add_action('wp_ajax_nopriv_event_pagination_bar_mobile', array($this, 'event_pagination_bar_mobile'));

		add_action('wp_ajax_event_order', array($this, 'event_order'));
		add_action('wp_ajax_nopriv_event_order', array($this, 'event_order'));

		add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
		add_action('wp_loaded', array($this, 'register_all_scripts'));

		add_shortcode('eventful', array($this, 'eventful_shortcode_render'));

		$this->suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) || (defined('WP_DEBUG') && WP_DEBUG) ? '' : '.min';

		add_action('wp_footer', function () {
			global $eventful_google_fonts;

			if (empty($eventful_google_fonts)) {
				return;
			}

			// Remove duplicates.
			$fonts = array_unique($eventful_google_fonts);

			// Build the Google Fonts URL.
			$google_fonts_url = add_query_arg(
				array(
					'family'  => implode('|', $fonts),
					'display' => 'swap', // optional, but recommended.
				),
				'https://fonts.googleapis.com/css'
			);

			wp_enqueue_style(
				'eventful-google-fonts',
				esc_url($google_fonts_url),
				array(),
				EVENTFUL_VERSION
			);
		});
	}

	/**
	 * Live preview Scripts and Styles.
	 */
	public function admin_scripts()
	{
		$current_screen        = get_current_screen();
		$the_current_post_type = is_object($current_screen) ? $current_screen->post_type : '';
		if ('eventful' === $the_current_post_type) {
			// CSS Files.
			wp_enqueue_style('eventful-icofont');
			wp_enqueue_style('swiper-bundle');
			wp_enqueue_style('eventful-grid');
			wp_enqueue_style('eventful-style');

			// JS Files.
			wp_enqueue_script('swiper-bundle');
			wp_enqueue_script('eventful-script', EVENTFUL_DIR_URL . 'src/Admin/assets/js/preview' . $this->suffix . '.js', array('eventful-swiper', 'eventful-bxslider'), EVENTFUL_VERSION, true);
		}
	}

	/**
	 * Register the All scripts for the public-facing side of the site.
	 *
	 * @since    2.2.0
	 */
	public function register_all_scripts()
	{
		wp_register_style('eventful-icofont', EVENTFUL_DIR_URL . 'src/Frontend/assets/css/icofont.min.css', array(), EVENTFUL_VERSION, 'all');
		wp_register_style('swiper-bundle', EVENTFUL_DIR_URL . 'src/Frontend/assets/css/swiper-bundle' . $this->suffix . '.css', array(), EVENTFUL_VERSION, 'all');
		wp_register_style('bxslider', EVENTFUL_DIR_URL . 'src/Frontend/assets/css/jquery.bxslider' . $this->suffix . '.css', array(), EVENTFUL_VERSION, 'all');

		wp_register_style('eventful-grid', EVENTFUL_DIR_URL . 'src/Frontend/assets/css/ta-grid' . $this->suffix . '.css', array(), EVENTFUL_VERSION, 'all');
		wp_register_style('eventful-style', EVENTFUL_DIR_URL . 'src/Frontend/assets/css/eventful' . $this->suffix . '.css', array('swiper-bundle'), EVENTFUL_VERSION, 'all');

		$g_option         = get_option('eventful_settings');
		$eventful_custom_css  = isset($g_option['eventful_custom_css']) ? $g_option['eventful_custom_css'] : '';
		if (!empty($eventful_custom_css)) {
			wp_add_inline_style('eventful-style', $eventful_custom_css);
		}

		wp_register_script('swiper-bundle', EVENTFUL_DIR_URL . 'src/Frontend/assets/js/swiper-bundle' . $this->suffix . '.js', array('jquery'), EVENTFUL_VERSION, true);
		wp_register_script('bxslider', EVENTFUL_DIR_URL . 'src/Frontend/assets/js/jquery.bxslider' . $this->suffix . '.js', array('jquery'), EVENTFUL_VERSION, true);
		wp_register_script('eventful-lazy', EVENTFUL_DIR_URL . 'src/Frontend/assets/js/eventful-lazyload' . $this->suffix . '.js', array('jquery'), EVENTFUL_VERSION, true);
		wp_register_script('eventful-script', EVENTFUL_DIR_URL . 'src/Frontend/assets/js/scripts' . $this->suffix . '.js', array('jquery'), EVENTFUL_VERSION, true);
		wp_localize_script(
			'eventful-script',
			'ta_eventful',
			array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'nonce'   => wp_create_nonce('eventful_nonce'),
			)
		);

		$eventful_custom_js = isset($g_option['eventful_custom_js']) ? $g_option['eventful_custom_js'] : '';
		if (!empty($eventful_custom_js)) {
			wp_add_inline_script('eventful-script', $eventful_custom_js);
		}
	}

	/**
	 * Event Ajax Pagination.
	 */
	public static function event_grid_ajax()
	{
		if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'eventful_nonce')) {
			return false;
		}
		$views_id            = isset($_POST['id']) ? absint($_POST['id']) : '';
		$keyword             = isset($_POST['keyword']) ? sanitize_text_field(wp_unslash($_POST['keyword'])) : '';
		$taxonomy            = isset($_POST['taxonomy']) ? sanitize_text_field(wp_unslash($_POST['taxonomy'])) : '';
		$term_id             = isset($_POST['term_id']) ? sanitize_text_field(wp_unslash($_POST['term_id'])) : '';
		$eventful_lang       = isset($_POST['lang']) ? sanitize_text_field(wp_unslash($_POST['lang'])) : '';
		$paged               = isset($_POST['page']) ? sanitize_text_field(wp_unslash($_POST['page'])) : '';
		$selected_term_list  = isset($_POST['term_list']) ? wp_unslash($_POST['term_list']) : '';
		$layout        		 = get_post_meta($views_id, 'eventful_layouts', true);
		$layout_preset 		 = isset($layout['eventful_layout_preset']) ? $layout['eventful_layout_preset'] : '';
		$options  			 = get_post_meta($views_id, 'eventful_view_options', true);
		// Event display settings.
		$event_content_sorter              = isset($options['event_content_sorter']) ? $options['event_content_sorter'] : '';
		$query_args                       = EventfulQueryInside::get_filtered_content($options, $views_id, $layout_preset);
		$event_limit                       = isset($options['eventful_event_limit']) && !empty($options['eventful_event_limit']) ? $options['eventful_event_limit'] : 10000;
		$new_query_args                   = $query_args;
		$new_query_args['fields']         = 'ids';
		$new_query_args['posts_per_page'] = $event_limit;
		$query_event_ids                   = get_posts($new_query_args);
		$query_args                       = EventfulFunctions::modify_query_params($query_args, $keyword, $selected_term_list, $query_event_ids, $eventful_lang);
		$new_query_args                   = $query_args;
		$new_query_args['fields']         = 'ids';
		$new_query_args['posts_per_page'] = $event_limit;
		$total_posts                      = count(tribe_get_events($new_query_args));
		$event_offset   = 0;

		$event_limit                       = $total_posts;
		if ($event_limit > 0) {
			$event_per_page = isset($options['post_per_page']) ? $options['post_per_page'] : '';
			$event_per_page = ($event_per_page > $event_limit) ? $event_limit : $event_per_page;
			if ($event_limit < 1) {
				$total_page = 0;
			} else {
				$total_page = EventfulFunctions::eventful_max_pages($event_limit, $event_per_page);
			}
			$eventful_last_page_post   = EventfulFunctions::eventful_last_page_post($event_limit, $event_per_page, $total_page);
			$offset               = (int) $event_per_page * ($paged - 1);
			$query_args['offset'] = (int) $offset + (int) 0;
			if ($total_page == $paged) {
				$query_args['posts_per_page'] = $eventful_last_page_post;
			}
			if ($paged > $total_page) {
				return false;
			}
		}
		$query_args['paged'] = $paged;
		$event_filter = isset($options['event_filter']) ? $options['event_filter'] : 'latest';
		if ($event_filter === 'feature') {
			if (!isset($query_args['meta_query']) || !is_array($query_args['meta_query'])) {
				$query_args['meta_query'] = array();
			}
			$query_args['meta_query'][] = array(
				'key'   => '_tribe_featured',
				'value' => '1',
			);
		}
		$eventful_query           = new \WP_Query($query_args);
		$start_count = isset($offset) ? (int) $offset + 1 : 1;
		EventfulLoopHtml::eventful_get_posts($options, $layout_preset, $event_content_sorter, $eventful_query->posts, $views_id, $start_count);
		die();
	}

	/**
	 * Event Ajax Pagination.
	 */
	public static function event_pagination_bar()
	{
		if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'eventful_nonce')) {
			return false;
		}
		$views_id            = isset($_POST['id']) ? absint($_POST['id']) : '';
		$keyword             = isset($_POST['keyword']) ? sanitize_text_field(wp_unslash($_POST['keyword'])) : '';
		$eventful_lang            = isset($_POST['lang']) ? sanitize_text_field(wp_unslash($_POST['lang'])) : '';
		$paged               = isset($_POST['page']) ? sanitize_text_field(wp_unslash($_POST['page'])) : '';
		$selected_term_list  = isset($_POST['term_list']) ? wp_unslash($_POST['term_list']) : '';
		$options        	 = get_post_meta($views_id, 'eventful_view_options', true);
		$layout              = get_post_meta($views_id, 'eventful_layouts', true);
		$layout_preset       = isset($layout['eventful_layout_preset']) ? $layout['eventful_layout_preset'] : '';
		$query_args          = EventfulQueryInside::get_filtered_content($options, $views_id, $layout_preset);
		$new_query_args                   = $query_args;
		$new_query_args['fields']         = 'ids';
		$event_limit                       = isset($options['eventful_event_limit']) && !empty($options['eventful_event_limit']) ? $options['eventful_event_limit'] : 10000;
		$new_query_args['posts_per_page'] = $event_limit;
		$query_event_ids                   = get_posts($new_query_args);
		$query_args         = EventfulFunctions::modify_query_params($query_args, $keyword, $selected_term_list, $query_event_ids, $eventful_lang);
		$query_args['lang'] = '';
		$query_args['posts_per_page'] = -1;
		$events_found          = count(tribe_get_events($query_args));
		EventfulLoopHtml::eventful_pagination_bar($events_found, $options, $layout, $views_id, $paged);
		die();
	}

	/**
	 * Event Ajax mobile Pagination.
	 */
	public static function event_pagination_bar_mobile()
	{
		if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'eventful_nonce')) {
			return false;
		}
		$views_id            = isset($_POST['id']) ? absint($_POST['id']) : '';
		$keyword             = isset($_POST['keyword']) ? sanitize_text_field(wp_unslash($_POST['keyword'])) : '';
		$eventful_lang            = isset($_POST['lang']) ? sanitize_text_field(wp_unslash($_POST['lang'])) : '';
		$paged               = isset($_POST['page']) ? sanitize_text_field(wp_unslash($_POST['page'])) : '';
		$selected_term_list  = isset($_POST['term_list']) ? wp_unslash($_POST['term_list']) : '';
		$options        	 = get_post_meta($views_id, 'eventful_view_options', true);
		$layout              = get_post_meta($views_id, 'eventful_layouts', true);
		$layout_preset       = isset($layout['eventful_layout_preset']) ? $layout['eventful_layout_preset'] : '';
		$query_args          = EventfulQueryInside::get_filtered_content($options, $views_id, $layout_preset, 'on_mobile');
		$new_query_args                   = $query_args;
		$new_query_args['fields']         = 'ids';
		$event_limit                       = isset($options['eventful_event_limit']) && !empty($options['eventful_event_limit']) ? $options['eventful_event_limit'] : 10000;
		$new_query_args['posts_per_page'] = $event_limit;
		$query_event_ids                   = get_posts($new_query_args);
		$query_event_ids                   = array('');
		$query_args = EventfulFunctions::modify_query_params($query_args, $keyword, $selected_term_list, $query_event_ids, $eventful_lang);
		$query_args['posts_per_page'] = -1;
		$events_found  = count(tribe_get_events($query_args));
		EventfulLoopHtml::eventful_pagination_bar($events_found, $options, $layout, $views_id, $paged, 'on_mobile');
		die();
	}

	/**
	 * Event Ajax filter.
	 */
	public static function event_order()
	{
		if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'eventful_nonce')) {
			return false;
		}
		$views_id               = isset($_POST['id']) ? absint($_POST['id']) : '';
		$keyword                = isset($_POST['keyword']) ? sanitize_text_field(wp_unslash($_POST['keyword'])) : '';
		$eventful_lang               = isset($_POST['lang']) ? sanitize_text_field(wp_unslash($_POST['lang'])) : '';
		$selected_term_list     = isset($_POST['term_list']) ? wp_unslash($_POST['term_list']) : '';
		$layout                 = get_post_meta($views_id, 'eventful_layouts', true);
		$layout_preset          = isset($layout['eventful_layout_preset']) ? $layout['eventful_layout_preset'] : '';
		$options           		= get_post_meta($views_id, 'eventful_view_options', true);
		$event_content_sorter    = isset($options['event_content_sorter']) ? $options['event_content_sorter'] : '';
		$query_args             = EventfulQueryInside::get_filtered_content($options, $views_id, $layout_preset);

		$new_query_args                   = $query_args;
		$new_query_args['fields']         = 'ids';
		$event_limit                       = isset($options['eventful_event_limit']) && !empty($options['eventful_event_limit']) ? $options['eventful_event_limit'] : 10000;
		$new_query_args['posts_per_page'] = $event_limit;
		$query_event_ids                   = get_posts($new_query_args);
		$query_args                       = EventfulFunctions::modify_query_params($query_args, $keyword, $selected_term_list, $query_event_ids, $eventful_lang);

		$eventful_query                        = new \WP_Query($query_args);
		EventfulLoopHtml::eventful_get_posts($options, $layout_preset, $event_content_sorter, $eventful_query->posts, $views_id);
		die();
	}

	/**
	 * Function get layout from atts and create class depending on it.
	 *
	 * @since 2.0
	 * @param array $attribute attribute of this shortcode.
	 */
	public function eventful_shortcode_render($attribute)
	{
		if (empty($attribute['id'])) {
			return;
		}
		$eventful_gl_id = $attribute['id']; // Eventful global ID for Shortcode meta boxes.
		// Preset Layouts.
		$layout        	= get_post_meta($eventful_gl_id, 'eventful_layouts', true);
		$options  		= get_post_meta($eventful_gl_id, 'eventful_view_options', true);
		$options		= apply_filters('eventful_view_options', $options, $eventful_gl_id);

		$section_title 	= get_the_title($eventful_gl_id);
		ob_start();
		EventfulLoopHtml::eventful_html_show($options, $layout, $eventful_gl_id, $section_title);
		return ob_get_clean();
	}
}

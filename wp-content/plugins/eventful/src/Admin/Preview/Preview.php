<?php

/**
 * The admin backend preview.
 *
 * @link        https://themeatelier.com/
 * @since      2.4.0
 *
 * @package    eventful
 * @subpackage eventful/Admin
 */

namespace ThemeAtelier\Eventful\Admin\Preview;

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulQueryInside;
use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;
use ThemeAtelier\Eventful\Frontend\Helpers\EventfulLoopHtml;

/**
 * The admin preview.
 */
class Preview
{

	/**
	 * Script and style suffix
	 *
	 * @since 2.4.0
	 * @access protected
	 * @var string
	 */
	protected $main;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.4.0
	 */
	public function __construct()
	{
		$this->eventful_preview_action();
		$this->main      = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG || defined('WP_DEBUG') && WP_DEBUG ? '' : '.min';
	}

	/**
	 * Public Action
	 *
	 * @return void
	 */
	private function eventful_preview_action()
	{
		add_action('wp_ajax_admin_event_grid_ajax', array($this, 'admin_event_grid_ajax'));
		add_action('wp_ajax_nopriv_admin_event_grid_ajax', array($this, 'admin_event_grid_ajax'));

		add_action('wp_ajax_admin_event_pagination_bar', array($this, 'admin_event_pagination_bar'));
		add_action('wp_ajax_nopriv_admin_event_pagination_bar', array($this, 'admin_event_pagination_bar'));

		add_action('wp_ajax_admin_event_pagination_bar_mobile', array($this, 'admin_event_pagination_bar_mobile'));
		add_action('wp_ajax_nopriv_admin_event_pagination_bar_mobile', array($this, 'admin_event_pagination_bar_mobile'));

		add_action('wp_ajax_admin_event_order', array($this, 'admin_event_order'));
		add_action('wp_ajax_nopriv_admin_event_order', array($this, 'admin_event_order'));

		add_shortcode('eventful', array($this, 'eventful_shortcode_render'));

		// Admin Preview.
		add_action('wp_ajax_eventful_admin_preview', array($this, 'eventful_admin_preview'));
	}

	/**
	 * Event Ajax Pagination.
	 */
	public static function admin_event_grid_ajax()
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

		$settings = array();
		parse_str($_POST['data'], $settings); // phpcs:ignore
		$view_options       = $settings['eventful_view_options'];
		$layout         	= $settings['eventful_layouts'];
		$layout_preset 		= isset($layout['eventful_layout_preset']) ? $layout['eventful_layout_preset'] : '';
		$event_content_sorter              = isset($view_options['event_content_sorter']) ? $view_options['event_content_sorter'] : '';
		$query_args                       = EventfulQueryInside::get_filtered_content($view_options, $views_id, $layout_preset);
		$event_limit                       = isset($view_options['eventful_event_limit']) && !empty($view_options['eventful_event_limit']) ? $view_options['eventful_event_limit'] : 10000;
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
			$event_per_page = isset($view_options['post_per_page']) ? $view_options['post_per_page'] : '';
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
		$event_filter = isset($view_options['event_filter']) ? $view_options['event_filter'] : 'latest';
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

		EventfulLoopHtml::eventful_get_posts($view_options, $layout_preset, $event_content_sorter, $eventful_query->posts, $views_id);
		die();
	}

	/**
	 * Admin Event Ajax Pagination.
	 */
	public static function admin_event_pagination_bar()
	{
		if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'eventful_nonce')) {
			return false;
		}
		$views_id            = isset($_POST['id']) ? absint($_POST['id']) : '';
		$keyword             = isset($_POST['keyword']) ? sanitize_text_field(wp_unslash($_POST['keyword'])) : '';
		$taxonomy            = isset($_POST['taxonomy']) ? sanitize_text_field(wp_unslash($_POST['taxonomy'])) : '';
		$term_id             = isset($_POST['term_id']) ? sanitize_text_field(wp_unslash($_POST['term_id'])) : '';
		$eventful_lang            = isset($_POST['lang']) ? sanitize_text_field(wp_unslash($_POST['lang'])) : '';
		$paged               = isset($_POST['page']) ? sanitize_text_field(wp_unslash($_POST['page'])) : '';
		$selected_term_list  = isset($_POST['term_list']) ? wp_unslash($_POST['term_list']) : '';
		$settings 			 = array();
		parse_str($_POST['data'], $settings); // phpcs:ignore
		$view_options         = $settings['eventful_view_options'];
		$layout         	= $settings['eventful_layouts'];
		$layout_preset       = isset($layout['eventful_layout_preset']) ? $layout['eventful_layout_preset'] : '';
		$pagination_type     = isset($view_options['event_pagination_type']) ? $view_options['event_pagination_type'] : '';
		$pagination_type     = isset($view_options['event_pagination_type_mobile']) ? $view_options['event_pagination_type_mobile'] : '';
		$query_args          = EventfulQueryInside::get_filtered_content($view_options, $views_id, $layout_preset);

		$new_query_args                   = $query_args;
		$new_query_args['fields']         = 'ids';
		$event_limit                       = isset($view_options['eventful_event_limit']) && !empty($view_options['eventful_event_limit']) ? $view_options['eventful_event_limit'] : 10000;
		$new_query_args['posts_per_page'] = $event_limit;
		$query_event_ids                   = get_posts($new_query_args);
		$query_args         = EventfulFunctions::modify_query_params($query_args, $keyword, $selected_term_list, $query_event_ids, $eventful_lang);
		$query_args['lang'] = '';
		$query_args['posts_per_page'] = -1;
		$events_found          = count(tribe_get_events($query_args));
		EventfulLoopHtml::eventful_pagination_bar($events_found, $view_options, $layout, $views_id, $paged);
		die();
	}

	/**
	 * Admin Event Ajax Pagination Mobile.
	 */
	public static function admin_event_pagination_bar_mobile()
	{
		if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'eventful_nonce')) {
			return false;
		}
		$views_id            = isset($_POST['id']) ? absint($_POST['id']) : '';
		$keyword             = isset($_POST['keyword']) ? sanitize_text_field(wp_unslash($_POST['keyword'])) : '';
		$taxonomy            = isset($_POST['taxonomy']) ? sanitize_text_field(wp_unslash($_POST['taxonomy'])) : '';
		$term_id             = isset($_POST['term_id']) ? sanitize_text_field(wp_unslash($_POST['term_id'])) : '';
		$eventful_lang            = isset($_POST['lang']) ? sanitize_text_field(wp_unslash($_POST['lang'])) : '';
		$paged               = isset($_POST['page']) ? sanitize_text_field(wp_unslash($_POST['page'])) : '';
		$selected_term_list  = isset($_POST['term_list']) ? wp_unslash($_POST['term_list']) : '';
		$settings = array();
		parse_str($_POST['data'], $settings); // phpcs:ignore
		$view_options         = $settings['eventful_view_options'];
		$layout         	= $settings['eventful_layouts'];
		$layout_preset       = isset($layout['eventful_layout_preset']) ? $layout['eventful_layout_preset'] : '';
		$pagination_type     = isset($view_options['event_pagination_type']) ? $view_options['event_pagination_type'] : '';
		$pagination_type     = isset($view_options['event_pagination_type_mobile']) ? $view_options['event_pagination_type_mobile'] : '';
		$query_args          = EventfulQueryInside::get_filtered_content($view_options, $views_id, $layout_preset, 'on_mobile');
		$tax_settings        = array();
		$new_query_args                   = $query_args;
		$new_query_args['fields']         = 'ids';
		$event_limit                       = isset($view_options['eventful_event_limit']) && !empty($view_options['eventful_event_limit']) ? $view_options['eventful_event_limit'] : 10000;
		$new_query_args['posts_per_page'] = $event_limit;
		$query_event_ids                   = get_posts($new_query_args);
		$query_event_ids                   = array('');
		$query_args = EventfulFunctions::modify_query_params($query_args, $keyword, $selected_term_list, $query_event_ids, $eventful_lang);
		$query_args['posts_per_page'] = -1;
		$events_found  = count(tribe_get_events($query_args));
		EventfulLoopHtml::eventful_pagination_bar($events_found, $view_options, $layout, $views_id, $paged, 'on_mobile');
		die();
	}

	/**
	 * Admin Event Ajax filter.
	 */
	public static function admin_event_order()
	{
		if (isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'eventful_nonce')) {
			return false;
		}
		$views_id               = isset($_POST['id']) ? absint($_POST['id']) : '';
		$keyword                = isset($_POST['keyword']) ? sanitize_text_field(wp_unslash($_POST['keyword'])) : '';
		$taxonomy               = isset($_POST['taxonomy']) ? sanitize_text_field(wp_unslash($_POST['taxonomy'])) : '';
		$term_id                = isset($_POST['term_id']) ? sanitize_text_field(wp_unslash($_POST['term_id'])) : '';
		$eventful_lang               = isset($_POST['lang']) ? sanitize_text_field(wp_unslash($_POST['lang'])) : '';
		$selected_term_list     = isset($_POST['term_list']) ? wp_unslash($_POST['term_list']) : '';

		$settings = array();
		parse_str($_POST['data'], $settings); // phpcs:ignore
		$view_options         = $settings['eventful_view_options'];
		$layout         = $settings['eventful_layouts'];
		$layout_preset          = isset($layout['eventful_layout_preset']) ? $layout['eventful_layout_preset'] : '';
		$pagination_type        = isset($view_options['event_pagination_type']) ? $view_options['event_pagination_type'] : '';
		$pagination_type_mobile = isset($view_options['event_pagination_type_mobile']) ? $view_options['event_pagination_type_mobile'] : '';
		$event_content_sorter    = isset($view_options['event_content_sorter']) ? $view_options['event_content_sorter'] : '';
		$query_args             = EventfulQueryInside::get_filtered_content($view_options, $views_id, $layout_preset);

		$new_query_args                   = $query_args;
		$new_query_args['fields']         = 'ids';
		$event_limit                       = isset($view_options['eventful_event_limit']) && !empty($view_options['eventful_event_limit']) ? $view_options['eventful_event_limit'] : 10000;
		$new_query_args['posts_per_page'] = $event_limit;
		$query_event_ids                   = get_posts($new_query_args);
		$query_args                       = EventfulFunctions::modify_query_params($query_args, $keyword, $selected_term_list, $query_event_ids, $eventful_lang);
		$eventful_query                        = new \WP_Query($query_args);

		EventfulLoopHtml::eventful_get_posts($view_options, $layout_preset, $event_content_sorter, $eventful_query->posts, $views_id);
		die();
	}

	/**
	 * Eventful Admin Preview.
	 *
	 * @since 2.4.0
	 */
	public function eventful_admin_preview()
	{
		$nonce = isset($_POST['ajax_nonce']) ? sanitize_text_field(wp_unslash($_POST['ajax_nonce'])) : ''; // phpcs:ignore
		if (! wp_verify_nonce($nonce, 'eventful_metabox_nonce')) {
			return;
		}

		$settings = array();
		parse_str($_POST['data'], $settings); // phpcs:ignore
		$event_id            = $settings['post_ID'];
		// Preset Layouts.
		$options  			= $settings['eventful_view_options'];
		$layouts            = $settings['eventful_layouts'];
		$enqueue_fonts             = array();
		// Google fonts.
		$section_title_typography           = isset($options['section_title_typography']) ? $options['section_title_typography'] : '';
		$thumb_archive_typography           = isset($options['thumb_archive_typography']) ? $options['thumb_archive_typography'] : '';
		$event_title_typography             = isset($options['event_title_typography']) ? $options['event_title_typography'] : '';
		$event_fildes_typography         	= isset($options['event_fildes_typography']) ? $options['event_fildes_typography'] : '';
		$event_content_typography   		= isset($options['event_content_typography']) ? $options['event_content_typography'] : '';
		$all_fonts                        	= array();
		$eventful_typography   = array();
		$eventful_typography[] = $section_title_typography;
		$eventful_typography[] = $thumb_archive_typography;
		$eventful_typography[] = $event_title_typography;
		$eventful_typography[] = $event_fildes_typography;
		$eventful_typography[] = $event_content_typography;
		$eventful_typography[] = isset($options['read_more_typography']) ? $options['read_more_typography'] : array(
			'font-family'        => '',
			'font-weight'        => '600',
			'subset'             => '',
			'font-size'          => '12',
			'tablet-font-size'   => '12',
			'mobile-font-size'   => '10',
			'line-height'        => '18',
			'tablet-line-height' => '18',
			'mobile-line-height' => '16',
			'letter-spacing'     => '0',
			'text-align'         => 'left',
			'text-transform'     => 'uppercase',
			'type'               => '',
			'unit'               => 'px',
		);

		if (! empty($eventful_typography)) {
			foreach ($eventful_typography as $font) {
				if (isset($font['font-family']) && isset($font['type']) && 'google' === $font['type']) {
					$variant     = (isset($font['font-weight']) && '' !== $font['font-weight']) ? ':' . $font['font-weight'] : '';
					$all_fonts[] = $font['font-family'] . $variant;
				}
			}
		}
		if ($all_fonts) {
			$enqueue_fonts[] = $all_fonts;
		}

		// Enqueue Google fonts.
		if (! empty($enqueue_fonts)) {
			echo '<link rel="stylesheet" href="' . esc_url( 'https://fonts.googleapis.com/css?family=' . implode( '|', array_merge( ...$enqueue_fonts ) ) ) . '" media="all">';
		}
?>
		<div class="eventful_preview_header">
			<div class="eventful_dot_wrap">
				<span class="dot dot-red"></span>
				<span class="dot dot-yellow"></span>
				<span class="dot dot-success"></span>
			</div>
		</div>
		<?php
		$section_title = get_the_title($event_id);
		EventfulLoopHtml::eventful_html_show($options, $layouts, $event_id, $section_title);

		?>
		<script src="<?php echo esc_url(EVENTFUL_PLUGINS_URL . '/src/Admin/assets/js/preview' . $this->main . '.js'); ?>" id="eventful-script-js"></script>

<?php
		die();
	}
}

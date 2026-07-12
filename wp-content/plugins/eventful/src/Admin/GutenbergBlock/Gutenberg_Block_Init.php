<?php

/**
 * The plugin elementor addons.
 *
 * @link       https://themeatelier.net/
 * @since      1.0.0
 *
 * @package    eventful
 * @subpackage eventful/Admin/GutenbergBlock
 * @author     ThemeAtelier <themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\Eventful\Admin\GutenbergBlock;

// Exit if accessed directly.
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Gutenberg Block Initializer.
 */
class Gutenberg_Block_Init
{

	/**
	 * Script and style suffix
	 *
	 * @since 2.5.3
	 * @access protected
	 * @var string
	 */
	protected $suffix;
	/**
	 * Custom Gutenberg Block Initializer.
	 */
	public function __construct()
	{
		$this->suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG || defined('WP_DEBUG') && WP_DEBUG ? '' : '.min';
		
		add_action('init', array($this, 'eventful_gutenberg_shortcode_block'));
		add_action('enqueue_block_editor_assets', array($this, 'eventful_block_editor_assets'));
	}

	/**
	 * Register block editor script for backend.
	 */
	public function eventful_block_editor_assets()
	{
		wp_enqueue_script(
			'eventful-shortcode-block',
			plugins_url('/GutenbergBlock/build/index.js', __DIR__),
			array('jquery'),
			EVENTFUL_VERSION,
			true
		);

		/**
		 * Register block editor css file enqueue for backend.
		 */
		wp_enqueue_style('eventful-icofont');
		wp_enqueue_style('swiper-bundle');

		wp_enqueue_style('eventful-grid');
		wp_enqueue_style('eventful-style');
	}

	/**
	 * Eventful Shortcode list.
	 *
	 * @return array
	 */
	public function eventful_post_list()
	{
		$shortcodes = get_posts(
			array(
				'post_type'      => 'eventful',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			)
		);


		if (count($shortcodes) < 1) {
			return array();
		}

		return array_map(
			function ($shortcode) {
				return (object) array(
					'id'    => absint($shortcode->ID),
					'title' => esc_html($shortcode->post_title),
				);
			},
			$shortcodes
		);
	}

	/**
	 * Register Gutenberg shortcode block.
	 */
	public function eventful_gutenberg_shortcode_block()
	{
		/**
		 * Register block editor js file enqueue for backend.
		 */

		wp_register_script('eventful-gd-scripts', EVENTFUL_DIR_URL . 'src/Frontend/assets/js/scripts.min.js', array('jquery'), EVENTFUL_VERSION, true);
		wp_register_style('template_editor_css', EVENTFUL_DIR_URL . 'src/Admin/GutenbergBlock/build/style-index.css', array(), EVENTFUL_VERSION, 'all');

		// Localize script
		wp_localize_script(
			'eventful-gd-scripts',
			'ta_eventful',
			array(
				'ajax_url'      => admin_url('admin-ajax.php'),
				'nonce'         => wp_create_nonce('eventful_nonce'),
				'loadScript'    => EVENTFUL_DIR_URL . 'src/Frontend/assets/js/scripts.js',
				'link'          => esc_url(admin_url('post-new.php?post_type=eventful')),
				'dir_url'          => EVENTFUL_DIR_URL,
				'shortCodeList' => $this->eventful_post_list(),
			)
		);

		/**
		 * Register Gutenberg block on server-side.
		 */
		register_block_type(
			'the-events-calendar/shortcode-block',
			array(
				'title'           => __('Eventful Layout', 'eventful'),
				'description'     => __('Events Slider, Carousel, List, and Filter Bar blocks for The Events Calendar.', 'eventful'),
				'category'        => 'tribe-events',
				'icon'            => 'calendar',
				'editorStyle'     => 'file:./build/index.css',
				'style'           => 'file:./build/style-index.css',
				'attributes'      => array(
					'shortcode'        => array(
						'type'    => 'string',
						'default' => '0',
					),
					'shortcodeContent' => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'is_admin'         => array(
						'type'    => 'boolean',
						'default' => is_admin(),
					),
					'preview'            => array(
						'type'    => 'boolean',
						'default' => false,
					),
				),
				'example'  => array(
					'attributes' => array(
						'preview' => true,
					),
				),
				'editor_script'   	=> array(
					'swiper-bundle',
					'eventful-lazy',
					'eventful-gd-scripts',
				),
				'editor_style'    	=> array('template_editor_css'),
				'render_callback' 	=> array($this, 'eventful_render_shortcode'),
			)
		);
	}

	public function eventful_render_shortcode($attributes)
	{
		if ($attributes['preview']) {
			return '<div><img src="' . EVENTFUL_DIR_URL . 'src/Admin/GutenbergBlock/assets/carousel_normal.svg"/></div>';
		}

		if (is_null($attributes['shortcode']) || '' === $attributes['shortcode']) {
			return __('<i></i>', 'eventful');
		}

		if (! $attributes['is_admin']) {
			return do_shortcode('[eventful id="' . sanitize_text_field($attributes['shortcode']) . '"]');
		}

		// Display edit button in admin
		$edit_page_link = get_edit_post_link(sanitize_text_field($attributes['shortcode']));
		return '<div id="' . uniqid() . '"><a href="' . $edit_page_link . '" target="_blank" class="eventful_block_edit_button">Edit View </a>' . do_shortcode('[eventful id="' . sanitize_text_field($attributes['shortcode']) . '"]') . '</div>';
	}
}

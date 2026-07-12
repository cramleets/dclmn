<?php

namespace ThemeAtelier\Eventful\Admin\Views;

use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;
use ThemeAtelier\Eventful\Admin\Views\Generator\EventfulLayout;
use ThemeAtelier\Eventful\Admin\Views\Generator\EventfulFilterPost;
use ThemeAtelier\Eventful\Admin\Views\Generator\EventfulDisplay;
use ThemeAtelier\Eventful\Admin\Views\Generator\EventfulCarousel;
use ThemeAtelier\Eventful\Admin\Views\Generator\EventfulTypography;
use ThemeAtelier\Eventful\Admin\Views\Generator\EventfulShortcode;
use ThemeAtelier\Eventful\Admin\Views\Generator\EventfulDetailSettings;
use ThemeAtelier\Eventful\Admin\Views\Generator\EventfulTimeline;

if (!defined('ABSPATH')) {
	die;
} // Cannot access directly.

class EventfulMetaboxes
{
	/**
	 * Layout Metabox function.
	 *
	 * @param string $prefix The meta-key for this metabox.
	 * @return void
	 */
	public static function layout_metabox($prefix)
	{
		/**
		 * Preview metabox.
		 *
		 * @param string $prefix The metabox main Key.
		 * @return void
		 */
		Eventful::createMetabox(
			'eventful_live_preview',
			array(
				'title'             => __('Live Preview', 'eventful'),
				'post_type'         => 'eventful',
				'show_restore'      => false,
				'eventful_shortcode' => false,
				'context'           => 'normal',
			)
		);
		Eventful::createSection(
			'eventful_live_preview',
			array(
				'fields' => array(
					array(
						'type' => 'preview',
					),
				),
			)
		);
		Eventful::createMetabox(
			$prefix,
			array(
				'title'        => esc_html__('Eventful', 'eventful'),
				'post_type'    => 'eventful',
				'show_restore' => false,
				'context'      => 'normal',
				'theme'      => 'light',
			)
		);

		EventfulLayout::section($prefix);
	}

	/**
	 * Option Metabox function
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function option_metabox($prefix)
	{
		Eventful::createMetabox(
			$prefix,
			array(
				'title'        	=> esc_html__('View Options', 'eventful'),
				'post_type'    	=> 'eventful',
				'show_restore' 	=> false,
				'nav'        	=> 'inline',
				'theme'        	=> 'light',
			)
		);

		EventfulFilterPost::section($prefix);
		EventfulDisplay::section($prefix);
		EventfulCarousel::section($prefix);
		EventfulTimeline::section($prefix);
		EventfulDetailSettings::section($prefix);
		EventfulTypography::section($prefix);
	}
	/**
	 * Shortcode Metabox function
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function shortcode_metabox($prefix)
	{
		Eventful::createMetabox(
			$prefix,
			array(
				'title'        => esc_html__('How to use', 'eventful'),
				'post_type'    => 'eventful',
				'context'      => 'side',
				'show_restore' => false,
			)
		);

		EventfulShortcode::section($prefix);
	}

	/**
	 * Page Builder Metabox function
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function page_builders_metabox( $prefix ) {
		Eventful::createMetabox(
			$prefix,
			array(
				'title'        => __( 'Page Builders', 'eventful' ),
				'post_type'    => 'eventful',
				'context'      => 'side',
				'show_restore' => false,
			)
		);

		Eventful::createSection(
			$prefix,
			array(
				'fields' => array(
					array(
						'type'      => 'shortcode',
						'shortcode' => false,
						'class'     => 'eventful-admin-sidebar',
					),
				),
			)
		);
	}

	/**
	 * Shortcode Metabox function
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function promotional_metabox( $prefix ) {
		Eventful::createMetabox(
			$prefix,
			array(
				'title'        => __( 'Additional Features in Pro', 'eventful' ),
				'post_type'    => 'eventful',
				'context'      => 'side',
				'show_restore' => false,
			)
		);

		Eventful::createSection(
			$prefix,
			array(
				'fields' => array(
					array(
						'type'      => 'shortcode',
						'shortcode' => 'pro_notice',
						'class'     => 'eventful-admin-sidebar',
					),
				),
			)
		);
	}

}

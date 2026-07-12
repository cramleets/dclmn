<?php

/**
 * "Replace Layout" settings page (Read-Only for Free Version).
 *
 * A dedicated menu that lets users VIEW The Events Calendar's default output
 * replacements — on the events archive, category, tag and search pages, and the
 * Related Events section. All fields are disabled in the free version (read-only).
 *
 * Settings are stored in the `eventful_replace_layout` option. Each page entry is
 * an enable switcher plus a fieldset holding the chosen layout (`which_shortcode`)
 * and, for list pages, how the events are loaded (`replace_way`). The frontend
 * counterpart is ThemeAtelier\Eventful\Includes\Eventful_Replace_Layout.
 *
 * @package    Eventful
 * @subpackage Eventful/admin/views
 */

namespace ThemeAtelier\Eventful\Admin\Views;

use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;
use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;

if (! defined('ABSPATH')) {
	die;
} // Cannot access pages directly.

/**
 * Replace Layout settings - Read-Only for Free Version.
 */
class EventfulReplaceLayout
{

	/**
	 * Create the Replace Layout settings page (read-only).
	 *
	 * @param string $prefix The settings option key.
	 * @return void
	 */
	public static function settings($prefix)
	{
		$capability = EventfulFunctions::eventful_dashboard_capability();

		Eventful::createOptions(
			$prefix,
			array(
				'menu_title'       => esc_html__('Replace Layout', 'eventful'),
				'menu_type'        => 'submenu',
				'menu_slug'        => 'eventful-replace-layout',
				'theme'            => 'light',
				'show_all_options' => false,
				'show_search'      => false,
				'show_footer'      => false,
				'show_bar_menu'    => false,
				'show_reset_section'    => false,
				'class'            => 'eventful-settings eventful-replace-layout-readonly',
				'framework_title'  => esc_html__('Replace Layout', 'eventful'),
				'menu_capability'  => $capability,
			)
		);

		Eventful::createSection(
			$prefix,
			array(
				'fields' => array_merge(
					array(
						array(
							'type'    => 'subheading',
							'content' => sprintf(
								/* translators: %s: Eventful Pro upgrade link. */
								esc_html__('Want to redesign or replace the default Events Calendar archive, category, tag, and related events sections with an attractive Eventful layout? %s ', 'eventful'),
								'<i><a href="https://wpeventful.com/pricing/?utm_source=eventful_plugin&utm_medium=replace_layout&utm_campaign=regular" target="_blank" rel="noopener noreferrer">' . esc_html__('Upgrade To Pro!', 'eventful') . '</a></i>'
							),
						),
					),
					// List pages — these show a list of events, so they support the
					// "how to load events" control.
					self::page_fields(
						'post_type-tribe_events',
						esc_html__('Event Archive Page', 'eventful'),
						esc_html__('The main events listing page (your site\'s "/events/" archive).', 'eventful'),
						true,
					),
					self::page_fields(
						'tax-tribe_events_cat',
						esc_html__('Event Category Page', 'eventful'),
						esc_html__('Pages that list the events in a single event category.', 'eventful'),
						true
					),
					self::page_fields(
						'post_tag',
						esc_html__('Event Tag Page', 'eventful'),
						esc_html__('Pages that list the events sharing a single tag.', 'eventful'),
						true
					),
					// Related — not a list page, so the layout fully replaces the
					// section and the "how to load events" control is omitted.
					self::page_fields(
						'related_events',
						esc_html__('Related Events', 'eventful'),
						esc_html__('The "Related Events" section shown beneath a single event. The chosen layout replaces it.', 'eventful'),
						false
					)
				),
			)
		);
	}

	/**
	 * Build the two fields (enable switcher + layout fieldset) for one page entry.
	 * All fields are disabled in the free version.
	 *
	 * Keeping this in one helper guarantees every page entry shares the exact same
	 * structure and option keys, which the frontend handler relies on.
	 *
	 * @param string $enable_id  Option key for the enable switcher (also the page key).
	 * @param string $title      Human-friendly page name.
	 * @param string $desc       Short description of what the page is.
	 * @param bool   $is_listing Whether this is a list page (adds the load-mode radio).
	 * @return array<int,array<string,mixed>> Two field definitions.
	 */
	private static function page_fields($enable_id, $title, $desc, $is_listing)
	{
		$fieldset_fields = array(
			array(
				'id'          => 'which_shortcode',
				'class'       => 'which_shortcode',
				'type'        => 'select',
				'title'       => esc_html__('Select a Layout', 'eventful'),
				'subtitle'    => esc_html__('Choose which Eventful layout to show here.', 'eventful'),
				'placeholder' => esc_html__('Select a layout', 'eventful'),
				'options'     => 'posts',
				'chosen'      => true,
				'sortable'    => false,
				'multiple'    => false,
				'disabled'    => true,
				'query_args'  => array(
					'post_type'      => 'eventful',
					'posts_per_page' => -1,
				),
			),
		);

		return array(
			array(
				'id'         => $enable_id,
				'type'       => 'switcher',
				'title'      => $title,
				'title_help' => '<div class="eventful-info-desc">' . $desc . '</div>',
				'text_on'    => esc_html__('Enabled', 'eventful'),
				'text_off'   => esc_html__('Disabled', 'eventful'),
				'text_width' => 100,
				'disabled'   => true,
				'class'    	 => 'switcher_pro_only',
			),
		);
	}
}

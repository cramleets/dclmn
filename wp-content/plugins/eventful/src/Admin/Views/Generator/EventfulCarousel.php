<?php

namespace ThemeAtelier\Eventful\Admin\Views\Generator;

use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;

if (!defined('ABSPATH')) {
	die;
} // Cannot access directly.

/**
 * The Carousel building class.
 */

class EventfulCarousel
{
	/**
	 * Carousel section metabox.
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function section($prefix)
	{
		Eventful::createSection(
			$prefix,
			array(
				'title'  => esc_html__('Carousel Controls', 'eventful'),
				'icon'   => 'icofont-settings',
				'fields' => array(
					array(
						'type' => 'section_tab',
						'tabs' => array(

							array(
								'title' => esc_html__('Carousel Control', 'eventful'),
								'icon'  => 'icofont-settings',
								'fields' => array(
									array(
										'id'         => 'eventful_slides_to_scroll',
										'type'       => 'column',
										'title'      => esc_html__('Slide To Scroll', 'eventful'),
										'desc'   => esc_html__('Number of event(s) to scroll at a time.', 'eventful'),
										'title_help' => '<i class="icofont-imac"></i> <b>' . esc_html__('Large Desktop', 'eventful') . '</b> - ' . esc_html__('width', 'eventful') . ' &gt; 1200px<br>' . '<i class="icofont-monitor"></i> <b>' . esc_html__('Desktop', 'eventful') . '</b> - ' . esc_html__('width', 'eventful') . ' &gt; 992px<br>' . '<i class="icofont-laptop-alt"></i> <b>' . esc_html__('Tablet', 'eventful') . '</b> - ' . esc_html__('width', 'eventful') . ' &gt; 768px<br>' . '<i class="icofont-ipad"></i> <b>' . esc_html__('Mobile Landscape', 'eventful') . '</b> - ' . esc_html__('width', 'eventful') . ' &gt; 576px<br>' . '<i class="icofont-android-tablet"></i> <b>' . esc_html__('Mobile', 'eventful') . '</b> - ' . esc_html__('width', 'eventful') . ' &lt;= 576px',
										'unit'       => false,
										'default'    => array(
											'lg_desktop' => '1',
											'desktop'    => '1',
											'tablet'     => '1',
											'mobile_landscape'     => '1',
											'mobile'     => '1',
										),
										'dependency' => array('eventful_layout_preset', '!=', 'ticker', 'any'),
									),
									array(
										'id'         => 'eventful_autoplay',
										'type'       => 'switcher',
										'title'      => esc_html__('AutoPlay', 'eventful'),
										'subtitle' => esc_html__('Enable or disable automatic slide rotation.', 'eventful'),
										'text_on'    => esc_html__('Enabled', 'eventful'),
										'text_off'   => esc_html__('Disabled', 'eventful'),
										'text_width' => 94,
										'default'    => true,
										'dependency' => array('eventful_layout_preset', '!=', 'ticker', 'any'),
									),
									array(
										'id'         => 'eventful_autoplay_speed',
										'type'       => 'spinner',
										'title'      => esc_html__('AutoPlay Speed', 'eventful'),
										'subtitle' => esc_html__('Control the delay time between slides during autoplay.', 'eventful'),
										'sanitize'   => 'eventful_sanitize_number_field',
										'default'    => '2000',
										'min'        => 0,
										'max'        => 10000,
										'step'       => 100,
										'unit'       => 'ms',
										'dependency' => array('eventful_autoplay|eventful_layout_preset', '==|!=', 'true|ticker', 'any'),
									),
									array(
										'id'         => 'eventful_carousel_speed',
										'type'       => 'spinner',
										'title'      => esc_html__('Carousel Speed', 'eventful'),
										'subtitle' => esc_html__('Adjust the transition animation speed between slides.', 'eventful'),
										'sanitize'   => 'eventful_sanitize_number_field',
										'default'    => '600',
										'min'        => 0,
										'max'        => 20000,
										'step'       => 100,
										'unit'       => 'ms',
										'dependency' => array('eventful_layout_preset', '!=', 'ticker', 'any'),
									),
									array(
										'id'         => 'eventful_pause_hover',
										'type'       => 'switcher',
										'title'      => esc_html__('Pause on Hover', 'eventful'),
										'subtitle' => esc_html__('Pause the carousel animation when the mouse hovers over it.', 'eventful'),
										'text_on'    => esc_html__('Enabled', 'eventful'),
										'text_off'   => esc_html__('Disabled', 'eventful'),
										'text_width' => 94,
										'default'    => true,
										'dependency' => array('eventful_autoplay', '==', 'true', 'any'),
									),
									array(
										'id'         => 'eventful_infinite_loop',
										'type'       => 'switcher',
										'title'      => esc_html__('Infinite Loop', 'eventful'),
										'subtitle' => esc_html__('Enable continuous looping of carousel slides.', 'eventful'),
										'text_on'    => esc_html__('Enabled', 'eventful'),
										'text_off'   => esc_html__('Disabled', 'eventful'),
										'text_width' => 94,
										'default'    => true,
										'dependency' => array('eventful_layout_preset', '!=', 'ticker', 'any'),
									),
									array(
										'id'         => 'eventful_lazy_load',
										'type'       => 'switcher',
										'title'      => esc_html__('Lazy Load', 'eventful'),
										'subtitle' => esc_html__('Load images only when they are about to enter the viewport for better performance.', 'eventful'),
										'text_on'    => esc_html__('Enabled', 'eventful'),
										'text_off'   => esc_html__('Disabled', 'eventful'),
										'text_width' => 94,
										'default'    => true,
										'dependency' => array('eventful_layout_preset|eventful_slide_effect', '!=|not-any', 'ticker|cube,flip', 'any'),
									),
									array(
										'id'         => 'preloader_color',
										'type'       => 'color',
										'title'      => esc_html__('Preloader Color', 'eventful'),
										'subtitle' => esc_html__('Set the color of the image preloader shown while slides are loading.', 'eventful'),
										'dependency' => array('eventful_layout_preset|eventful_slide_effect|eventful_lazy_load', '!=|not-any|==', 'ticker|cube,flip|true', 'any'),
									),
									array(
										'id'       => 'eventful_carousel_direction',
										'type'     => 'button_set',
										'title'      => esc_html__('Carousel Direction', 'eventful'),
										'subtitle' => esc_html__('Choose the reading and sliding direction of the carousel.', 'eventful'),
										'options'  => array(
											'ltr' => esc_html__('Right to Left', 'eventful'),
											'rtl' => esc_html__('Left to Right', 'eventful'),
										),
										'default'  => 'ltr',
									),
									array(
										'id'         => 'eventful_slide_effect',
										'type'       => 'select',
										'title'      => esc_html__('Transition Effect', 'eventful'),
										'subtitle' => esc_html__('Select how slides transition between each other (e.g., fade, cube, or flip).', 'eventful'),
										'options'    => array(
											'slide'     => esc_html__('Slide', 'eventful'),
											'fade'      => esc_html__('Fade (Pro)', 'eventful'),
											'coverflow' => esc_html__('Coverflow (Pro)', 'eventful'),
											'cube'      => esc_html__('Cube (Pro)', 'eventful'),
											'flip'      => esc_html__('Flip (Pro)', 'eventful'),
										),
										'default'    => 'slide',
										'attributes' => array(
											'style' => 'width: 200px;',
										),
										'dependency' => array('eventful_layout_preset', '!=', 'ticker', 'any'),
									),
									array(
										'id'         => 'slider_style',
										'type'       => 'layout_preset',
										'title'      => esc_html__('Slider Style', 'eventful'),
										'subtitle' => esc_html__('Select how slides transition between each other (e.g., fade, cube, or flip).', 'eventful'),
										'options'    => array(
											'slide'  => array(
												'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/slider.svg',
												'text'  => esc_html__('Slide', 'eventful'),
											),
											'fade'  => array(
												'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/fade.svg',
												'text'  => esc_html__('Fade', 'eventful'),
												'pro_only'	=> true,
											),
											'coverflow'  => array(
												'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/coverflow.svg',
												'text'  => esc_html__('Cover Flow', 'eventful'),
												'pro_only'	=> true,
											),
											'cube'  => array(
												'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/cube.svg',
												'text'  => esc_html__('Cube', 'eventful'),
												'pro_only'	=> true,
											),
											'flip'  => array(
												'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/flip.svg',
												'text'  => esc_html__('Flip', 'eventful'),
												'pro_only'	=> true,
											),
										),
										'default'    => 'slide',
										'dependency' => array('eventful_layout_preset', 'any', 'slider', 'any'),
									),
								),
							),
							array(
								'title' => esc_html__('Navigation', 'eventful'),
								'icon'  => 'icofont-rounded-double-right',
								'fields' => array(
									// Navigation Settings.
									array(
										'id'         => 'eventful_navigation',
										'type'       => 'button_set',
										'title'      => esc_html__('Navigation', 'eventful'),
										'subtitle' => esc_html__('Show or hide the carousel navigation arrows.', 'eventful'),
										'options'    => array(
											'show'           => esc_html__('Show', 'eventful'),
											'hide'           => esc_html__('Hide', 'eventful'),
											'hide_on_mobile' => esc_html__('Hide on Mobile', 'eventful'),
										),
										'default'    => 'show',
										'dependency' => array('eventful_layout_preset', '!=', 'ticker', 'any'),
									),
									array(
										'id'         => 'eventful_carousel_nav_position',
										'type'       => 'select',
										'title'      => esc_html__('Select Position', 'eventful'),
										'subtitle' => esc_html__('Choose where the navigation arrows appear in the carousel.', 'eventful'),
										'options'    => array(
											'top_right'                   => esc_html__('Top right', 'eventful'),
											'top_center'                  => esc_html__('Top center', 'eventful'),
											'top_left'                    => esc_html__('Top left', 'eventful'),
											'bottom_left'                 => esc_html__('Bottom left (Pro)', 'eventful'),
											'bottom_center'               => esc_html__('Bottom center (Pro)', 'eventful'),
											'bottom_right'                => esc_html__('Bottom right (Pro)', 'eventful'),
											'vertically_center_outer'     => esc_html__('Vertically center outer (Pro)', 'eventful'),
											'vertical_center_inner'       => esc_html__('Vertically center inner (Pro)', 'eventful'),
											'vertical_center_inner_hover' => esc_html__('Vertically center inner on hover (Pro)', 'eventful'),
										),
										'default'    => 'top_right',
										'attributes' => array(
											'style' => 'width: 300px;',
										),
										'dependency' => array('eventful_navigation|eventful_layout_preset', '!=|!=', 'hide|ticker', 'any'),
									),
									array(
										'id'         => 'navigation_icons',
										'type'       => 'button_set',
										'title'      => esc_html__('Choose an Icon', 'eventful'),
										'subtitle' => esc_html__('Select an icon style for the navigation arrows.', 'eventful'),
										'options'    => array(
											'icofont-rounded'     	=> array(
												'text' => '<i class="icofont-rounded-right"></i>',
											),
											'icofont-double'     	=> array(
												'text' => '<i class="icofont-double-right"></i>',
												'pro_only'	=> true,
											),
											'icofont-bubble'     	=> array(
												'text' => '<i class="icofont-bubble-right"></i>',
												'pro_only'	=> true,
											),
											'icofont-long-arrow'     	=> array(
												'text' => '<i class="icofont-long-arrow-right"></i>',
												'pro_only'	=> true,
											),
											'icofont-arrow'     	=> array(
												'text' => '<i class="icofont-arrow-right"></i>',
												'pro_only'	=> true,
											),
											'icofont-caret'     	=> array(
												'text' => '<i class="icofont-caret-right"></i>',
												'pro_only'	=> true,
											),
											'icofont-thin'     	=> array(
												'text' => '<i class="icofont-thin-right"></i>',
												'pro_only'	=> true,
											),
										),
										'default'    => 'icofont-rounded',
										'dependency' => array('eventful_navigation|eventful_layout_preset', '!=|!=', 'hide|ticker', 'any'),
									),
									array(
										'id'         => 'eventful_nav_icon_size',
										'type'       => 'spinner',
										'title'      => esc_html__('Arrow Icon Size', 'eventful'),
										'subtitle' => esc_html__('Set the size of the navigation arrow icons.', 'eventful'),
										'sanitize'   => 'eventful_sanitize_number_field',
										'default'    => '18',
										'min'        => 0,
										'max'        => 100,
										'unit'       => 'px',
										'dependency' => array('eventful_navigation|eventful_layout_preset', '!=|!=', 'hide|ticker', 'any'),
									),
									array(
										'id'         => 'navigation_icons_border_radius',
										'type'       => 'spacing',
										'title'      => esc_html__('Navigation Border Radius', 'eventful'),
										'subtitle' => esc_html__('Control the border radius of navigation arrow backgrounds.', 'eventful'),
										'sanitize'   => 'eventful_sanitize_number_array_field',
										'all'        => true,
										'min'        => 0,
										'max'        => 100,
										'units'      => array('px', '%'),
										'default'    => array(
											'all' => '0',
										),
										'dependency' => array('eventful_navigation|eventful_layout_preset', '!=|!=', 'hide|ticker', 'any'),
									),
									array(
										'id'         => 'eventful_nav_colors',
										'type'       => 'color_group',
										'title'      => esc_html__('Navigation Color', 'eventful'),
										'subtitle' => esc_html__('Set colors for navigation icons, background, border, and hover states.', 'eventful'),
										'options'    => array(
											'color'              => esc_html__('Color', 'eventful'),
											'hover-color'        => esc_html__('Hover Color', 'eventful'),
											'bg'                 => esc_html__('Background', 'eventful'),
											'hover-bg'           => esc_html__('Hover Background', 'eventful'),
											'border-color'       => esc_html__('Border', 'eventful'),
											'hover-border-color' => esc_html__('Hover Border', 'eventful'),
										),
										'dependency' => array('eventful_navigation|eventful_layout_preset', '!=|!=', 'hide|ticker', 'any'),
									),
								),
							),
							array(
								'title' => esc_html__('Pagination', 'eventful'),
								'icon'  => 'icofont-flikr',
								'fields' => array(
									// Pagination Settings.
									array(
										'id'         => 'eventful_pagination',
										'type'       => 'button_set',
										'title'      => esc_html__('Pagination', 'eventful'),
										'subtitle' => esc_html__('Show or hide the carousel pagination indicators.', 'eventful'),
										'options'    => array(
											'show'           => esc_html__('Show', 'eventful'),
											'hide'           => esc_html__('Hide', 'eventful'),
											'hide_on_mobile' => esc_html__('Hide on Mobile', 'eventful'),
										),
										'default'    => 'show',
										'dependency' => array('eventful_layout_preset', '!=', 'ticker', 'any'),
									),
									array(
										'id'         => 'bullet_types',
										'type'       => 'button_set',
										'title'      => esc_html__('Pagination Type', 'eventful'),
										'subtitle' => esc_html__('Choose the style of pagination to display.', 'eventful'),
										'options'    => array(
											'dots'   => array(
												'text'	=> esc_html__('Dots', 'eventful'),
											),
											'number' => array(
												'text'	=> esc_html__('Number', 'eventful'),
												'pro_only'	=> true,
											),
										),
										'default'    => 'dots',
										'dependency' => array('eventful_pagination|eventful_layout_preset', '!=|!=', 'hide|ticker', 'any'),
									),
									array(
										'id'         => 'eventful_pagination_color_set',
										'type'       => 'fieldset',
										'class'      => 'eventful-pagination-color-set',
										'title'      => esc_html__('Pagination Color', 'eventful'),
										'subtitle' => esc_html__('Customize colors for pagination dots or numbers.', 'eventful'),
										'fields'     => array(
											array(
												'id'         => 'eventful_pagination_color',
												'type'       => 'color_group',
												'options'    => array(
													'color'        => esc_html__('Color', 'eventful'),
													'active-color' => esc_html__('Active Color', 'eventful'),
												),
												'dependency' => array('bullet_types', '==', 'dots', 'any'),
											),
										),
										'dependency' => array('eventful_pagination|eventful_layout_preset', '!=|!=', 'hide|ticker', 'any'),
									),
									array(
										'id'         => 'eventful_dynamicBullets',
										'type'       => 'checkbox',
										'title'      => esc_html__('Dynamic Pagination', 'eventful'),
										'subtitle' => esc_html__('Enable dynamic pagination bullets for better navigation with large slide sets.', 'eventful'),
										'default'    => false,
										'dependency' => array('eventful_pagination|eventful_layout_preset', '!=|!=', 'hide|ticker', 'any'),
									),
								),
							),
							array(
								'title' => esc_html__('Miscellaneous', 'eventful'),
								'icon'  => 'icofont-listine-dots',
								'fields' => array(
									// Miscellaneous Settings.
									array(
										'id'         => 'eventful_adaptive_height',
										'type'       => 'switcher',
										'title'      => esc_html__('Adaptive Carousel Height', 'eventful'),
										'subtitle' => esc_html__('Dynamically adjust the event carousel height based on each slide’s content height.', 'eventful'),
										'default'    => false,
										'text_on'    => esc_html__('Enabled', 'eventful'),
										'text_off'   => esc_html__('Disabled', 'eventful'),
										'text_width' => 94,
										'dependency' => array('eventful_layout_preset|eventful_slide_effect', '!=|not-any', 'ticker|flip,cube', 'any'),
									),
									array(
										'id'         => 'eventful_accessibility',
										'type'       => 'switcher',
										'title'      => esc_html__('Tab and Key Navigation', 'eventful'),
										'subtitle' => esc_html__('Enable keyboard navigation using tab and arrow keys for carousel accessibility.', 'eventful'),
										'default'    => true,
										'text_on'    => esc_html__('Enabled', 'eventful'),
										'text_off'   => esc_html__('Disabled', 'eventful'),
										'text_width' => 94,

										'dependency' => array('eventful_layout_preset', '!=', 'ticker', 'any'),
									),
									array(
										'id'         => 'touch_swipe',
										'type'       => 'switcher',
										'title'      => esc_html__('Touch Swipe', 'eventful'),
										'subtitle' => esc_html__('Allow users to navigate the carousel using touch gestures on mobile devices.', 'eventful'),
										'text_on'    => esc_html__('Enabled', 'eventful'),
										'text_off'   => esc_html__('Disabled', 'eventful'),
										'text_width' => 94,
										'default'    => true,
										'dependency' => array('eventful_layout_preset', '!=', 'ticker', 'any'),
									),
									array(
										'id'         => 'slider_draggable',
										'type'       => 'switcher',
										'title'      => esc_html__('Mouse Draggable', 'eventful'),
										'subtitle' => esc_html__('Enable dragging slides using the mouse on desktop devices.', 'eventful'),
										'text_on'    => esc_html__('Enabled', 'eventful'),
										'text_off'   => esc_html__('Disabled', 'eventful'),
										'text_width' => 94,
										'default'    => true,
										'dependency' => array('eventful_layout_preset', '!=', 'ticker', 'any'),
									),
									array(
										'id'         => 'slider_mouse_wheel',
										'type'       => 'switcher',
										'title'      => esc_html__('Mouse Wheel', 'eventful'),
										'subtitle' => esc_html__('Navigate carousel slides using the mouse wheel.', 'eventful'),
										'text_on'    => esc_html__('Enabled', 'eventful'),
										'text_off'   => esc_html__('Disabled', 'eventful'),
										'text_width' => 94,
										'default'    => false,
										'dependency' => array('eventful_layout_preset', '!=', 'ticker', 'any'),
									),
								), // End of fields array.
							),
						),
					),
				),
			)
		); // Carousel Controls section end.
	}
}

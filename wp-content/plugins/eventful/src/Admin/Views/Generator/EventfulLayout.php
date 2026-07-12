<?php

namespace ThemeAtelier\Eventful\Admin\Views\Generator;

use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;

if (!defined('ABSPATH')) {
	die;
} // Cannot access directly.

/**
 * The Layout building class.
 */
class EventfulLayout
{

	/**
	 * Layout metabox section.
	 *
	 * @param string $prefix The metabox key.
	 * @return void
	 */
	public static function section($prefix)
	{
		Eventful::createSection(
			$prefix,
			array(
				'fields' => array(
					array(
						'type'  => 'metabox_branding',
						'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/eventful-logo.svg',
						'after' => '<i class="icofont-life-ring"></i> Support',
						'link'  => 'https://wordpress.org/support/plugin/eventful/#new-topic-0',
						'class' => 'eventful-admin-header',
					),
					array(
						'id'      => 'eventful_layout_preset',
						'type'    => 'layout_preset',
						'title'   => esc_html__('Layout Preset', 'eventful'),
						'class'   => 'eventful-layout-preset',
						'options' => array(
							'carousel_layout'  => array(
								'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/carousel.svg',
								'text'  => esc_html__('Carousel', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'events-carousel/',
							),
							'slider'  => array(
								'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/slider_layout.svg',
								'text'  => esc_html__('Slider', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'events-slider/',
							),
							'grid_layout'      => array(
								'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/grid.svg',
								'text'  => esc_html__('Grid', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'events-grid/',
							),
							'minimal_list'      => array(
								'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/list-minimal.svg',
								'text'  => esc_html__('Minimal List', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'events-minimal-list/',
							),
							'timeline'  => array(
								'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/timeline.svg',
								'text'  => esc_html__('Timeline', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'events-timeline/',
							),
							'masonry_layout'      => array(
								'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/masonry.svg',
								'text'  => esc_html__('Masonry', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'events-masonry/',
								'pro_only'        => true,
							),
							'list_layout'  => array(
								'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/list.svg',
								'text'  => esc_html__('List', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'events-list/',
								'pro_only'        => true,
							),
							'table_layout'  => array(
								'image' => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/table.svg',
								'text'  => esc_html__('Table', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'events-table/',
								'pro_only'        => true,
							),
						),
						'default' => 'carousel_layout',
					),
					array(
						'id'         => 'timeline_style',
						'type'       => 'layout_preset',
						'title'      => esc_html__('Timeline Style', 'eventful'),
						'class'      => 'eventful_sub_preset',
						'options'    => array(
							'vertical'   => array(
								'image'           => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/timeline.svg',
								'text'            => esc_html__('Vertical', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'vertical-timeline/',
							),
							'horizontal' => array(
								'image'           => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/horizontal-timeline.svg',
								'text'            => esc_html__('Horizontal', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'horizontal-timeline/',
								'pro_only'        => true,
							),
						),
						'default'    => 'vertical',
						'dependency' => array('eventful_layout_preset', '==', 'timeline'),
					),
					array(
						'id'         => 'carousel_style',
						'type'       => 'layout_preset',
						'title'      => esc_html__('Carousel Style', 'eventful'),
						'class'      => 'eventful_sub_preset',
						'options'    => array(
							'standard'  => array(
								'image'           => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/carousel.svg',
								'text'            => esc_html__('Standard', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'events-carousel/',
							),
							'ticker'    => array(
								'image'           => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/ticker.svg',
								'text'            => esc_html__('Ticker', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'events-ticker-carousel/',
								'pro_only'        => true,
							),
							'center'    => array(
								'image'           => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/centered.svg',
								'text'            => esc_html__('Center', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'center-carousel/',
								'pro_only'        => true,
							),
							'multi_row' => array(
								'image'           => EVENTFUL_DIR_URL . 'src/Admin/Framework/assets/img/multi-row.svg',
								'text'            => esc_html__('Multi Row', 'eventful'),
								'option_demo_url' => EVENTFUL_DEMO_URL . 'multi-row-carousel/',
								'pro_only'        => true,
							),
						),
						'default'    => 'standard',
						'dependency' => array('eventful_layout_preset', '==', 'carousel_layout'),
					),
				), // End of fields array.
			)
		);
	}
}

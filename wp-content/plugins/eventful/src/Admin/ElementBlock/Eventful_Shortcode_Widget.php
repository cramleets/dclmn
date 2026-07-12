<?php

/**
 * The plugin elementor widget.
 *
 * @link       https://themeatelier.net/
 * @since      2.8.2
 * @package    eventful.
 * @subpackage eventful/Admin.
 * @author     ThemeAtelier <themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\Eventful\Admin\ElementBlock;

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulLoopHtml;

/**
 * Elementor Eventful ShortCode Widget.
 */
class Eventful_Shortcode_Widget extends \Elementor\Widget_Base
{

	/**
	 * Get widget name.
	 *
	 * @since 2.8.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'eventful_shortcode';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.8.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return __('Eventful', 'eventful');
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.8.2
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'eicon-calendar';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 2.8.2
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories()
	{
		return array('basic');
	}

	/**
	 * Get all post list.
	 *
	 * @since 2.8.2
	 * @return array
	 */
	public function ta_eventful_post_list()
	{
		$post_list     = array();
		$ta_eventful_posts = new \WP_Query(
			array(
				'post_type'      => 'eventful',
				'post_status'    => 'publish',
				'posts_per_page' => 10000,
			)
		);
		$posts         = $ta_eventful_posts->posts;
		foreach ($posts as $post) {
			$post_list[$post->ID] = $post->post_title;
		}
		krsort($post_list);
		return $post_list;
	}

	/**
	 * Controls register.
	 *
	 * @return void
	 */
	protected function register_controls()
	{
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __('Content', 'eventful'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'ta_eventful_shortcode',
			array(
				'label'       => __('Eventful Shortcode(s)', 'eventful'),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => '',
				'options'     => $this->ta_eventful_post_list(),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render eventful shortcode widget output on the frontend.
	 *
	 * @since 2.8.2
	 * @access protected
	 */
	protected function render()
	{
		$settings          = $this->get_settings_for_display();
		$eventful_gl_id = $settings['ta_eventful_shortcode'];

		if ('' === $eventful_gl_id) {
			echo '<div style="text-align: center; margin-top: 0; padding: 10px" class="elementor-add-section-drag-title">' . esc_html__('Select a shortcode', 'eventful') . '</div>';
			return;
		}


		if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
			$layout        	= get_post_meta($eventful_gl_id, 'eventful_layouts', true);
			$options  		= get_post_meta($eventful_gl_id, 'eventful_view_options', true);

			$section_title 	= get_the_title($eventful_gl_id);
			EventfulLoopHtml::eventful_html_show($options, $layout, $eventful_gl_id, $section_title);
?>
			<script src="<?php echo esc_url(EVENTFUL_DIR_URL . 'src/Frontend/assets/js/scripts.js'); ?>"></script>
<?php
		} else {
			echo do_shortcode('[eventful id="' . $eventful_gl_id . '"]');
		}
	}
}

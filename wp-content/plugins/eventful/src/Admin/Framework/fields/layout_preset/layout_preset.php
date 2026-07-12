<?php if (! defined('ABSPATH')) {
	die;
} // Cannot access directly.
/**
 *
 * Field: layout_preset
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if (! class_exists('EventfulField_layout_preset')) {
	class EventfulField_layout_preset extends EventfulFields
	{

		public function __construct($field, $value = '', $unique = '', $where = '', $parent = '')
		{
			parent::__construct($field, $value, $unique, $where, $parent);
		}

		public function render()
		{

			$args = wp_parse_args(
				$this->field,
				array(
					'multiple' => false,
					'options'  => array(),
				)
			);

			$value = (is_array($this->value)) ? $this->value : array_filter((array) $this->value);

			echo wp_kses_post($this->field_before());

			if (! empty($args['options'])) {

				echo '<div class="eventful-siblings eventful--image-group" data-multiple="' . esc_attr($args['multiple']) . '">';

				$num = 1;

				foreach ($args['options'] as $key => $option) {

					$type               = ($args['multiple']) ? 'checkbox' : 'radio';
					$extra              = ($args['multiple']) ? '[]' : '';
					$active             = (in_array($key, $value)) ? ' eventful--active' : '';
					$checked            = (in_array($key, $value)) ? ' checked' : '';
					$pro_only           = isset($option['pro_only']) ? ' disabled' : '';
					$eventful_only_class = isset( $option['pro_only'] ) ? ' eventful-pro-only' : '';

					echo '<div class="eventful--sibling eventful--image' . esc_attr($active) . esc_attr($eventful_only_class) . '">';
					echo '<img src="' . esc_url($option['image']) . '" alt="' . esc_attr($option['text']) . '" />';
					echo '<input ' . esc_attr($pro_only) . ' type="' . esc_attr($type) . '" name="' . esc_attr($this->field_name($extra)) . '" value="' . esc_attr($key) . '"' . wp_kses_post($this->field_attributes()) . esc_attr($checked) . '/>';
					echo '<span class="ta-carousel-type">' . esc_html($option['text']);
					if (! empty($option['option_demo_url'])) {
						echo ' <a href="' . esc_url($option['option_demo_url']) . '" tooltip="' . esc_attr__('Demo', 'eventful') . '" class="eventful-live-demo-icon" target="_blank"><i class="icofont-external-link"></i></a>';
					}
					echo '</span>';
					echo '</div>';
				}

				echo '</div>';
			}

			echo '<div class="clear"></div>';

			echo wp_kses_post($this->field_after());
		}

		public function output()
		{

			$output    = '';
			$bg_image  = array();
			$important = (! empty($this->field['output_important'])) ? '!important' : '';
			$elements  = (is_array($this->field['output'])) ? join(',', $this->field['output']) : $this->field['output'];

			if (! empty($elements) && isset($this->value) && $this->value !== '') {
				$output = $elements . '{background-image:url(' . $this->value . ')' . $important . ';}';
			}

			$this->parent->output_css .= $output;

			return $output;
		}
	}
}

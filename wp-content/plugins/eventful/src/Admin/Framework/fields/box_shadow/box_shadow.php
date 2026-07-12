<?php
/**
 *
 * Field: Box-shadow
 *
 * @link       https://themeatelier.net/
 *
 * @package Eventful
 * @subpackage Eventful/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'EventfulField_box_shadow' ) ) {
	/**
	 *
	 * Field: border
	 *
	 * @since 2.0
	 * @version 2.0
	 */
	class EventfulField_box_shadow extends EventfulFields {
		/**
		 * The class constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */

		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}
		/**
		 * The render method.
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'horizontal_icon'        => esc_html__( 'X offset', 'eventful' ),
					'vertical_icon'          => esc_html__( 'Y offset', 'eventful' ),
					'blur_icon'              => esc_html__( 'Blur', 'eventful' ),
					'spread_icon'            => esc_html__( 'Spread', 'eventful' ),
					'horizontal_placeholder' => 'h-offset',
					'vertical_placeholder'   => 'v-offset',
					'blur_placeholder'       => 'blur',
					'spread_placeholder'     => 'spread',
					'horizontal'             => true,
					'vertical'               => true,
					'blur'                   => true,
					'spread'                 => true,
					'color'                  => true,
					'style'                  => true,
					'unit'                   => 'px',
				)
			);

			$default_value = array(
				'horizontal' => '0',
				'vertical'   => '0',
				'blur'       => '0',
				'spread'     => '0',
				'color'      => '#ddd',
				'style'      => 'outset',
			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$value = wp_parse_args( $this->value, $default_value );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="eventful--inputs">';

			$properties = array();

			foreach ( array( 'horizontal', 'vertical', 'blur', 'spread' ) as $prop ) {
				if ( ! empty( $args[ $prop ] ) ) {
					$properties[] = $prop;
				}
			}

			foreach ( $properties as $property ) {

				$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . $args[ $property . '_placeholder' ] . '"' : '';

				echo '<div class="eventful--input">';
				echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="eventful--label eventful--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . wp_kses_post( $placeholder ) . ' class="eventful-input-number eventful--is-unit" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="eventful--label eventful--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
				echo '</div>';

			}

			if ( ! empty( $args['style'] ) ) {
				echo '<div class="eventful--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[style]' ) ) . '">';
				foreach ( array( 'inset', 'outset' ) as $style ) {
					$selected = ( $value['style'] === $style ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $style ) . '"' . esc_attr( $selected ) . '>' . esc_attr( ucfirst( $style ) ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
			}

			echo '</div>';

			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . $default_value['color'] . '"' : '';
				echo '<div class="eventful-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" value="' . esc_attr( $value['color'] ) . '" class="eventful-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
			}

			echo '<div class="clear"></div>';

			echo wp_kses_post( $this->field_after() );

		}
	}
}

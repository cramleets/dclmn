<?php
/**
 *
 * Field: Image sizes
 *
 * @link       https://themeatelier.net/
 *
 * @package Eventful
 * @subpackage Eventful/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'EventfulField_image_sizes' ) ) {
	/**
	 *
	 * Field: Image sizes.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class EventfulField_image_sizes extends EventfulFields {
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
					'chosen'      => false,
					'multiple'    => false,
					'placeholder' => '',
				)
			);

			$this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			// Get the image sizes.
			global $_wp_additional_image_sizes;
			$sizes = array();

			foreach ( get_intermediate_image_sizes() as $_size ) {
				if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {

					$width  = get_option( "{$_size}_size_w" );
					$height = get_option( "{$_size}_size_h" );
					$crop   = (bool) get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

					$sizes[ $_size ] = ucfirst( "{$_size} - $crop:{$width}x{$height}" );

				} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

					$width  = $_wp_additional_image_sizes[ $_size ]['width'];
					$height = $_wp_additional_image_sizes[ $_size ]['height'];
					$crop   = $_wp_additional_image_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

					$sizes[ $_size ] = ucfirst( "{$_size} - $crop:{$width}X{$height}" );
				}
			}
			$sizes = array_merge(
				$sizes,
				array(
					'full'   => esc_html__( 'Original uploaded image', 'eventful' ),
					'custom' => esc_html__( 'Set custom size', 'eventful' ),
				)
			);

			if ( ! empty( $sizes ) ) {

				$multiple_name    = ( $args['multiple'] ) ? '[]' : '';
				$multiple_attr    = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
				$chosen_rtl       = ( is_rtl() ) ? ' chosen-rtl' : '';
				$chosen_attr      = ( $args['chosen'] ) ? ' class="eventful-chosen' . $chosen_rtl . '"' : '';
				$placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="' . $args['placeholder'] . '"' : '';

				if ( ! empty( $sizes ) ) {

					echo '<select name="' . esc_attr( $this->field_name( $multiple_name ) ) . '"' . esc_attr( $multiple_attr . $chosen_attr ) . wp_kses_post( $placeholder_attr ) . wp_kses_post($this->field_attributes()) . '>';// phpcs:ignore

					if ( $args['placeholder'] && empty( $args['multiple'] ) ) {
						if ( ! empty( $args['chosen'] ) ) {
							echo '<option value=""></option>';
						} else {
							echo '<option value="">' . esc_html( $args['placeholder'] ) . '</option>';
						}
					}

					foreach ( $sizes as $option_key => $option ) {

						if ( is_array( $option ) && ! empty( $option ) ) {

							echo '<optgroup label="' . esc_attr( $option_key ) . '">';

							foreach ( $option as $sub_key => $sub_value ) {
								$selected = ( in_array( $sub_key, $this->value ) ) ? ' selected' : '';
								echo '<option value="' . esc_attr( $sub_key ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $sub_value ) . '</option>';
							}

							echo '</optgroup>';

						} else {
							$selected = ( in_array( $option_key, $this->value ) ) ? ' selected' : '';
							echo '<option value="' . esc_attr( $option_key ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $option ) . '</option>';
						}
					}

					echo '</select>';

				} else {

					echo ! empty( $this->field['empty_message'] ) ? esc_html( $this->field['empty_message'] ) : esc_html__( 'No image sizes found.', 'eventful' );

				}
			}

			echo wp_kses_post( $this->field_after() );

		}

	}
}

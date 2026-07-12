<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: button_set
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'EventfulField_button_set' ) ) {
  class EventfulField_button_set extends EventfulFields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'multiple'   => false,
        'options'    => array(),
        'query_args' => array(),
      ) );

      $value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

      echo wp_kses_post( $this->field_before() );

      if ( isset( $this->field['options'] ) ) {

        $options = $this->field['options'];
        $options = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options, false, $args['query_args'] ) );

        if ( is_array( $options ) && ! empty( $options ) ) {

          echo '<div class="eventful-siblings eventful--button-group" data-multiple="'. esc_attr( $args['multiple'] ) .'">';

          foreach ( $options as $key => $option ) {

            $type    = ( $args['multiple'] ) ? 'checkbox' : 'radio';
            $extra   = ( $args['multiple'] ) ? '[]' : '';
            $active  = ( in_array( $key, $value ) || ( empty( $value ) && empty( $key ) )  ) ? ' eventful--active' : '';
            $checked = ( in_array( $key, $value ) || ( empty( $value ) && empty( $key ) ) ) ? ' checked' : '';
            $pro_only = isset($option['pro_only']) ? ' disabled' : '';

            echo '<div class="eventful--sibling ' . esc_attr($pro_only) . ' eventful--button'. esc_attr( $active ) .'">';
            echo '<input type="'. esc_attr( $type ) .'" ' . esc_attr($pro_only) . ' name="'. esc_attr( $this->field_name( $extra ) ) .'" value="'. esc_attr( $key ) .'"'. wp_kses_post($this->field_attributes()) . esc_attr( $checked ) .'/>';
            if (! empty($option['text'])) {
						  echo wp_kses_post($option['text']);
						} elseif (! empty($option['option_name'])) {
						  echo wp_kses_post($option['option_name']);
						} else {
						  echo wp_kses_post($option);
						}
            echo '</div>';

          }

          echo '</div>';

        } else {

          echo ( ! empty( $this->field['empty_message'] ) ) ? esc_attr( $this->field['empty_message'] ) : esc_html__( 'No data available.', 'eventful' );

        }

      }

      echo wp_kses_post( $this->field_after() );

    }

  }
}

<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'EventfulField_switcher' ) ) {
  class EventfulField_switcher extends EventfulFields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $active     = ( ! empty( $this->value ) ) ? ' eventful--active' : '';
      $text_on    = ( ! empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : esc_html__( 'On', 'eventful' );
      $text_off   = ( ! empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : esc_html__( 'Off', 'eventful' );
      $text_width = ( ! empty( $this->field['text_width'] ) ) ? ' style="width: '. esc_attr( $this->field['text_width'] ) .'px;"': '';

      echo wp_kses_post( $this->field_before() );

      echo '<div class="eventful--switcher'. esc_attr( $active ) .'"'. wp_kses_post($text_width) .'>';
      echo '<span class="eventful--on">'. esc_attr( $text_on ) .'</span>';
      echo '<span class="eventful--off">'. esc_attr( $text_off ) .'</span>';
      echo '<span class="eventful--ball"></span>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'"'. wp_kses_post($this->field_attributes()) .' />';
      echo '</div>';

      echo ( ! empty( $this->field['label'] ) ) ? '<span class="eventful--label">'. esc_attr( $this->field['label'] ) . '</span>' : '';

      echo wp_kses_post( $this->field_after() );

    }

  }
}

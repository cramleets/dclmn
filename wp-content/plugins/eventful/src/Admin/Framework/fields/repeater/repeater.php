<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: repeater
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;

if ( ! class_exists( 'EventfulField_repeater' ) ) {
  class EventfulField_repeater extends EventfulFields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'max'          => 0,
        'min'          => 0,
        'button_title' => '<i class="icofont-plus-circle"></i>',
      ) );

      if ( preg_match( '/'. preg_quote( '['. $this->field['id'] .']' ) .'/', $this->unique ) ) {

        echo '<div class="eventful-notice eventful-notice-danger">'. esc_html__( 'Error: Field ID conflict.', 'eventful' ) .'</div>';

      } else {

        echo wp_kses_post( $this->field_before() );

        echo '<div class="eventful-repeater-item eventful-repeater-hidden" data-depend-id="'. esc_attr( $this->field['id'] ) .'">';
        echo '<div class="eventful-repeater-content">';
        foreach ( $this->field['fields'] as $field ) {

          $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
          $field_unique  = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .'][0]' : $this->field['id'] .'[0]';

          Eventful::field( $field, $field_default, '___'. $field_unique, 'field/repeater' );

        }
        echo '</div>';
        echo '<div class="eventful-repeater-helper">';
        echo '<div class="eventful-repeater-helper-inner">';
        echo '<i class="eventful-repeater-sort icofont-drag"></i>';
        echo '<i class="eventful-repeater-clone icofont-copy-invert"></i>';
        echo '<i class="eventful-repeater-remove eventful-confirm icofont-close" data-confirm="'. esc_html__( 'Are you sure to delete this item?', 'eventful' ) .'"></i>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div class="eventful-repeater-wrapper eventful-data-wrapper" data-field-id="['. esc_attr( $this->field['id'] ) .']" data-max="'. esc_attr( $args['max'] ) .'" data-min="'. esc_attr( $args['min'] ) .'">';

        if ( ! empty( $this->value ) && is_array( $this->value ) ) {

          $num = 0;

          foreach ( $this->value as $key => $value ) {

            echo '<div class="eventful-repeater-item">';
            echo '<div class="eventful-repeater-content">';
            foreach ( $this->field['fields'] as $field ) {

              $field_unique = ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']['. $num .']' : $this->field['id'] .'['. $num .']';
              $field_value  = ( isset( $field['id'] ) && isset( $this->value[$key][$field['id']] ) ) ? $this->value[$key][$field['id']] : '';

              Eventful::field( $field, $field_value, $field_unique, 'field/repeater' );

            }
            echo '</div>';
            echo '<div class="eventful-repeater-helper">';
            echo '<div class="eventful-repeater-helper-inner">';
            echo '<i class="eventful-repeater-sort icofont-drag"></i>';
            echo '<i class="eventful-repeater-clone icofont-copy-invert"></i>';
            echo '<i class="eventful-repeater-remove eventful-confirm icofont-close" data-confirm="'. esc_html__( 'Are you sure to delete this item?', 'eventful' ) .'"></i>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            $num++;

          }

        }

        echo '</div>';

        echo '<div class="eventful-repeater-alert eventful-repeater-max">'. esc_html__( 'You cannot add more.', 'eventful' ) .'</div>';
        echo '<div class="eventful-repeater-alert eventful-repeater-min">'. esc_html__( 'You cannot remove more.', 'eventful' ) .'</div>';
        echo '<a href="#" class="button button-primary eventful-repeater-add">'. wp_kses_post($args['button_title']) .'</a>';

        echo wp_kses_post( $this->field_after() );

      }

    }

    public function enqueue() {

      if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
        wp_enqueue_script( 'jquery-ui-sortable' );
      }

    }

  }
}

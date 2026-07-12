<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: icon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if ( ! class_exists( 'EventfulField_icon' ) ) {
  class EventfulField_icon extends EventfulFields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args = wp_parse_args( $this->field, array(
        'button_title' => esc_html__( 'Add Icon', 'eventful' ),
        'remove_title' => esc_html__( 'Remove Icon', 'eventful' ),
      ) );

      echo wp_kses_post( $this->field_before() );

      $nonce  = wp_create_nonce( 'eventful_icon_nonce' );
      $hidden = ( empty( $this->value ) ) ? ' hidden' : '';

      echo '<div class="eventful-icon-select">';
      echo '<span class="eventful-icon-preview'. esc_attr( $hidden ) .'"><i class="'. esc_attr( $this->value ) .'"></i></span>';
      echo '<a href="#" class="button button-primary eventful-icon-add" data-nonce="'. esc_attr( $nonce ) .'">'. wp_kses_post($args['button_title']) .'</a>';
      echo '<a href="#" class="button eventful-warning-primary eventful-icon-remove'. esc_attr( $hidden ) .'">'. wp_kses_post($args['remove_title']) .'</a>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'" class="eventful-icon-value"'. wp_kses_post($this->field_attributes()) .' />';
      echo '</div>';

      echo wp_kses_post( $this->field_after() );

    }

    public function enqueue() {
      add_action( 'admin_footer', array( 'EventfulField_icon', 'add_footer_modal_icon' ) );
      add_action( 'customize_controls_print_footer_scripts', array( 'EventfulField_icon', 'add_footer_modal_icon' ) );
    }

    public static function add_footer_modal_icon() {
    ?>
      <div id="eventful-modal-icon" class="eventful-modal eventful-modal-icon hidden">
        <div class="eventful-modal-table">
          <div class="eventful-modal-table-cell">
            <div class="eventful-modal-overlay"></div>
            <div class="eventful-modal-inner">
              <div class="eventful-modal-title">
                <?php esc_html_e( 'Add Icon', 'eventful' ); ?>
                <div class="eventful-modal-close eventful-icon-close"></div>
              </div>
              <div class="eventful-modal-header">
                <input type="text" placeholder="<?php esc_html_e( 'Search...', 'eventful' ); ?>" class="eventful-icon-search" />
              </div>
              <div class="eventful-modal-content">
                <div class="eventful-modal-loading"><div class="eventful-loading"></div></div>
                <div class="eventful-modal-load"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php
    }

  }
}

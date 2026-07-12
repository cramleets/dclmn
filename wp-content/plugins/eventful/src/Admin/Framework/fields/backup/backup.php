<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;

if ( ! class_exists( 'EventfulField_backup' ) ) {
  class EventfulField_backup extends EventfulFields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $unique = $this->unique;
      $nonce  = wp_create_nonce( 'eventful_backup_nonce' );
      $export = add_query_arg( array( 'action' => 'eventful-export', 'unique' => $unique, 'nonce' => $nonce ), admin_url( 'admin-ajax.php' ) );

      echo wp_kses_post( $this->field_before() );

      echo '<textarea name="eventful_import_data" class="eventful-import-data"></textarea>';
      echo '<button type="submit" class="button button-primary eventful-confirm eventful-import" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html__( 'Import', 'eventful' ) .'</button>';
      echo '<hr />';
      echo '<textarea readonly="readonly" class="eventful-export-data">'. esc_attr( wp_json_encode( get_option( $unique ) ) ) .'</textarea>';
      echo '<a href="'. esc_url( $export ) .'" class="button button-primary eventful-export" target="_blank">'. esc_html__( 'Export & Download', 'eventful' ) .'</a>';
      echo '<hr />';
      echo '<button type="submit" name="eventful_transient[reset]" value="reset" class="button eventful-warning-primary eventful-confirm eventful-reset" data-unique="'. esc_attr( $unique ) .'" data-nonce="'. esc_attr( $nonce ) .'">'. esc_html__( 'Reset', 'eventful' ) .'</button>';

      echo wp_kses_post( $this->field_after() );

    }

  }
}

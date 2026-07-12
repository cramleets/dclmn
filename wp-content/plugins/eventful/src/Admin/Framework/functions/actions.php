<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;


/**
 * Populate the taxonomy terms list to the select option.
 *
 * @return void
 */
function eventful_get_terms() {
	$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	// Check for nonce security.
	if ( wp_verify_nonce( $nonce, 'eventful_metabox_nonce' ) ) {
		$capability = apply_filters( 'eventful_dashboard_capability', 'manage_options' );
		if ( current_user_can( $capability ) ) {
			$the_eventful_taxonomy = ( ! empty( $_POST['eventful_post_taxonomy'] ) ) ? sanitize_text_field( wp_unslash( $_POST['eventful_post_taxonomy'] ) ) : '';
			$ta_post_types    = get_post_types( array(), 'names' );
			$eventful_taxonomy     = $the_eventful_taxonomy ? $the_eventful_taxonomy : get_object_taxonomies( $ta_post_types, 'names' );
			if ( version_compare( get_bloginfo( 'version' ), '4.5', '>=' ) ) {
				$terms = get_terms( array( 'taxonomy' => $eventful_taxonomy ) );
			} else {
				$terms = get_terms( array( $eventful_taxonomy ) );
			}

			foreach ( $terms as $key => $value ) {
				echo '<option value="' . esc_attr( $value->term_id ) . '">' . esc_html( $value->name ) . '</option>';
			}
		} else {
				wp_send_json_error( array( 'error' => esc_html__( 'You do not have required permissions to access.', 'eventful' ) ) );
		}
	} else {
		wp_send_json_error( array( 'error' => esc_html__( 'Error: Nonce verification has failed. Please try again.', 'eventful' ) ) );
	}
}

	add_action( 'wp_ajax_eventful_get_terms', 'eventful_get_terms' );


if ( ! function_exists( 'eventful_get_icons' ) ) {
  function eventful_get_icons() {

    $nonce = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'eventful_icon_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'eventful' ) ) );
    }

    ob_start();

    Eventful::include_plugin_file( 'fields/icon/icofont.php' );

    $icon_lists_icofont = apply_filters( 'eventful_field_icon_add_icons_icofont', eventful_get_default_icons_icofont() );

    if ( ! empty( $icon_lists_icofont ) || ! empty($icon_lists_fontAwesome) ) {
      echo '<h3>'. esc_html__('IcoFont', 'eventful') .'</h3>';
      foreach ( $icon_lists_icofont as $list ) {
        echo ( count( $icon_lists_icofont ) >= 2 ) ? '<div class="eventful-icon-title">'. esc_attr( $list['title'] ) .'</div>' : '';

        foreach ( $list['icons'] as $icon ) {
          echo '<i title="'. esc_attr( $icon ) .'" class="'. esc_attr( $icon ) .'"></i>';
        }
      }
    } else {

      echo '<div class="eventful-error-text">'. esc_html__( 'No data available.', 'eventful' ) .'</div>';

    }

    $content = ob_get_clean();

    wp_send_json_success( array( 'content' => $content ) );

  }
  add_action( 'wp_ajax_eventful-get-icons', 'eventful_get_icons' );
}

/**
 *
 * Export
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'eventful_export' ) ) {
  function eventful_export() {

    $nonce  = ( ! empty( $_GET[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_GET[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_GET[ 'unique' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'eventful_backup_nonce' ) ) {
      die( esc_html__( 'Error: Invalid nonce verification.', 'eventful' ) );
    }

    if ( empty( $unique ) ) {
      die( esc_html__( 'Error: Invalid key.', 'eventful' ) );
    }

    // Export
    header('Content-Type: application/json');
    header('Content-disposition: attachment; filename=backup-'. gmdate( 'd-m-Y' ) .'.json');
    header('Content-Transfer-Encoding: binary');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo wp_json_encode( get_option( $unique ) );

    die();

  }
  add_action( 'wp_ajax_eventful-export', 'eventful_export' );
}

/**
 *
 * Import Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'eventful_import_ajax' ) ) {
  function eventful_import_ajax() {

    $nonce  = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_POST[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'unique' ] ) ) : '';
    $data   = ( ! empty( $_POST[ 'data' ] ) ) ? wp_kses_post_deep( json_decode( wp_unslash( trim( $_POST[ 'data' ] ) ), true ) ) : array();

    if ( ! wp_verify_nonce( $nonce, 'eventful_backup_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'eventful' ) ) );
    }

    if ( empty( $unique ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid key.', 'eventful' ) ) );
    }

    if ( empty( $data ) || ! is_array( $data ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: The response is not a valid JSON response.', 'eventful' ) ) );
    }

    // Success
    update_option( $unique, $data );

    wp_send_json_success();

  }
  add_action( 'wp_ajax_eventful-import', 'eventful_import_ajax' );
}

/**
 *
 * Reset Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'eventful_reset_ajax' ) ) {
  function eventful_reset_ajax() {

    $nonce  = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $unique = ( ! empty( $_POST[ 'unique' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'unique' ] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'eventful_backup_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'eventful' ) ) );
    }

    // Success
    delete_option( $unique );

    wp_send_json_success();

  }
  add_action( 'wp_ajax_eventful-reset', 'eventful_reset_ajax' );
}

/**
 *
 * Chosen Ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'eventful_chosen_ajax' ) ) {
  function eventful_chosen_ajax() {

    $nonce = ( ! empty( $_POST[ 'nonce' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : '';
    $type  = ( ! empty( $_POST[ 'type' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'type' ] ) ) : '';
    $term  = ( ! empty( $_POST[ 'term' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'term' ] ) ) : '';
    $query = ( ! empty( $_POST[ 'query_args' ] ) ) ? wp_kses_post_deep( $_POST[ 'query_args' ] ) : array();

    if ( ! wp_verify_nonce( $nonce, 'eventful_chosen_ajax_nonce' ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'eventful' ) ) );
    }

    if ( empty( $type ) || empty( $term ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid term ID.', 'eventful' ) ) );
    }

    $capability = apply_filters( 'eventful_chosen_ajax_capability', 'manage_options' );

    if ( ! current_user_can( $capability ) ) {
      wp_send_json_error( array( 'error' => esc_html__( 'Error: You do not have permission to do that.', 'eventful' ) ) );
    }

    // Success
    $options = EventfulFields::field_data( $type, $term, $query );

    wp_send_json_success( $options );

  }
  add_action( 'wp_ajax_eventful-chosen', 'eventful_chosen_ajax' );
}

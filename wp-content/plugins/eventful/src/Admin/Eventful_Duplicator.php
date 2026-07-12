<?php
/**
 * The Eventful Duplicator.
 *
 * @since        2.1.11
 *
 * @package    eventful
 * @subpackage eventful/admin
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

namespace ThemeAtelier\Eventful\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * The Slider Duplicator
 */
class Eventful_Duplicator {

	/**
	 * Construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_action_eventful_duplicate_shortcode', array( $this, 'eventful_duplicate_shortcode' ) );
		add_filter( 'post_row_actions', array( $this, 'eventful_duplicate_shortcode_link' ), 10, 2 );
	}

	/**
	 * Function creates product slider duplicate as a draft.
	 */
	public function eventful_duplicate_shortcode() {
		global $wpdb;
		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'eventful_duplicate_shortcode' === $_REQUEST['action'] ) ) ) {
			wp_die( esc_html__( 'No shortcode to duplicate has been supplied!', 'eventful' ) );
		}

		/*
		 * Nonce verification
		 */
		if ( ! isset( $_GET['eventful_duplicate_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['eventful_duplicate_nonce'] ) ), basename( __FILE__ ) ) ) {
			return;
		}

		/*
		 * Get the original shortcode id
		 */
		$event_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : absint( $_POST['post'] );

		$capability = apply_filters( 'eventful_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;

		if ( ! $show_ui && get_post_type( $event_id ) !== 'eventful' ) {
			wp_die( esc_html__( 'No shortcode to duplicate has been supplied!', 'eventful' ) );
		}
		// And all the original shortcode data then.
		$event = get_post( $event_id );

		$current_user    = wp_get_current_user();
		$new_post_author = $current_user->ID;

		/*
		 * if shortcode data exists, create the shortcode duplicate
		 */
		if ( isset( $event ) && null !== $event ) {

			// new shortcode data array.
			$args = array(
				'comment_status' => $event->comment_status,
				'ping_status'    => $event->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $event->post_content,
				'post_excerpt'   => $event->post_excerpt,
				'post_name'      => $event->post_name,
				'post_parent'    => $event->post_parent,
				'post_password'  => $event->post_password,
				'post_status'    => 'draft',
				'post_title'     => $event->post_title,
				'post_type'      => $event->post_type,
				'to_ping'        => $event->to_ping,
				'menu_order'     => $event->menu_order,
			);

			/*
			 * insert the shortcode by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );

			/*
			 * get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies( $event->post_type ); // Returns array of taxonomy names for post type, ex array("category", "post_tag").
			foreach ( $taxonomies as $taxonomy ) {
				$event_terms = wp_get_object_terms( $event_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $event_terms, $taxonomy, false );
			}

			$event_meta_infos = get_post_custom( $event_id );
			// Duplicate all post meta.
			foreach ( $event_meta_infos as $key => $values ) {
				foreach ( $values as $value ) {
					$value = wp_slash( maybe_unserialize( $value ) ); // Unserialize data to avoid conflicts.
					add_post_meta( $new_post_id, $key, $value );
				}
			}
			// Finally, redirect to the edit post screen for the new draft.
			wp_safe_redirect( esc_url( admin_url( 'edit.php?post_type=' . $event->post_type ) ) );
			exit;
		} else {
			wp_die( esc_html__( 'Shortcode creation failed, could not find original post: ', 'eventful' ) . esc_attr( $event_id ) );
		}
	}


	/**
	 * Add the duplicate link to action list for post_row_actions
	 *
	 * @param  array  $actions Actions.
	 * @param  object $event post.
	 * @return array
	 */
	public function eventful_duplicate_shortcode_link( $actions, $event ) {
		$capability = apply_filters( 'eventful_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		if ( $show_ui && 'eventful' === $event->post_type ) {
			$actions['duplicate'] = '<a href="' . wp_nonce_url( 'admin.php?action=eventful_duplicate_shortcode&post=' . $event->ID, basename( __FILE__ ), 'eventful_duplicate_nonce' ) . '" rel="permalink">' . __( 'Duplicate', 'eventful' ) . '</a>';
		}
		return $actions;
	}
}

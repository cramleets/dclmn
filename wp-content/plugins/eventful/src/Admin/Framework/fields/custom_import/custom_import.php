<?php
/**
 * Framework Custom_import field file.
 *
 * @link https://themeatelier.net
 * @since 2.0.0
 *
 * @package eventful
 * @subpackage eventful/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'EventfulField_custom_import' ) ) {
	/**
	 *
	 * Field: Custom_import
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class EventfulField_custom_import extends EventfulFields {

		/**
		 * Field constructor.
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
		 * Render field
		 *
		 * @return void
		 */
		public function render() {
			echo wp_kses_post( $this->field_before() );
			$eventful_shortcodes       = admin_url( 'edit.php?post_type=eventful' );
				echo '<p><input type="file" id="import" accept=".json, .csv"></p>';
				echo '<p><button type="button" class="import">Import</button></p>';
				echo '<a id="eventful_shortcode_link_redirect" href="' . esc_url( $eventful_shortcodes ) . '"></a>';
			echo wp_kses_post( $this->field_after() );
		}
	}
}

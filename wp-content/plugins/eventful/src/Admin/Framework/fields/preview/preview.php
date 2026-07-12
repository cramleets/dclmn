<?php
/**
 * Preview field file.
 *
 * @link http://themeatelier.com
 * @since 2.4.0
 *
 * @package eventful
 * @subpackage eventful/Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'EventfulField_preview' ) ) {
	/**
	 *
	 * Field: shortcode
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class EventfulField_preview extends EventfulFields {

		/**
		 * Shortcode field constructor.
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
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			echo '<div class="eventful_preview_box" id="eventful_preview_box"></div>';
		}

	}
}

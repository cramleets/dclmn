<?php
/**
 * Framework license field file.
 *
 * @link https://themeatelier.net
 * @since 2.0.0
 *
 * @package eventful
 * @subpackage eventful/Admin
 */

use ThemeAtelier\Eventful\License;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'EventfulField_license' ) ) {
	/**
	 *
	 * Field: license
	 *
	 * @since 2.2.4
	 * @version 2.2.4
	 */
	class EventfulField_license extends EventfulFields {
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
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			echo wp_kses_post($this->field_before());

			echo '<div class="eventful-license text-center">';
			echo '<h3>' . esc_html__('You\'re using Eventful Lite - No License Needed. Enjoy! 🙂', 'eventful') . '</h3>';

			echo '<p>'. esc_html__('Upgrade to Eventful Pro and unlock all the features.', 'eventful') . '</p>';
			echo '<a href="https://wpeventful.com/pricing/" target="_blank" class="button-secondary">'. esc_html__('Upgrade To Pro Now', 'eventful') . '</a>';

			echo '</div>';
			echo wp_kses_post($this->field_after());
		}

	}
}

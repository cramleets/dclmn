<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; 
} // Cannot access directly.

if ( ! class_exists( 'EventfulField_shortcode' ) ) {	
	/**
	 * EventfulField_shortcode
	 */
	class EventfulField_shortcode extends EventfulFields {
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
		 * Render method.
		 *
		 * @return void
		 */
		public function render() {
			// Get the Event ID.
			$post_id = get_the_ID();
			if ( !empty( $this->field['shortcode'] ) && 'manage_view' === $this->field['shortcode'] ) {
				echo ( ! empty( $post_id ) ) ? '<div class="eventful-scode-wrap-side"><p>To display your show, add the following shortcode into your event, custom event types, page, widget or block editor. If adding the show to your theme files, additionally include the surrounding PHP code <a href="https://wpeventful.com/docs/add-new-event-layout/#faq" target="_blank">see how</a>.</p><span class="eventful-shortcode-selectable">[eventful id="' . esc_attr( $post_id ) . '"]</span></div><div class="eventful-after-copy-text"><i class="icofont-check-circled"></i> Shortcode Copied to Clipboard! </div>' : '';
			} elseif ( !empty( $this->field['shortcode'] ) && 'pro_notice' === $this->field['shortcode'] ) {
				if ( ! empty( $post_id ) ) {
					echo '<div class="eventful_shortcode-area eventful-notice-wrapper">';
					echo '<div class="eventful-notice-heading">' . sprintf(
						/* translators: 1: start span tag, 2: close tag. */
						esc_html__( 'Additional Features in %1$sPRO%2$s', 'eventful' ),
						'<span>',
						'</span>'
					) . '</div>';

					echo '<p class="eventful-notice-desc">' . sprintf(
						/* translators: 1: start bold tag, 2: close tag. */
						esc_html__( 'Here are some additional features available in Pro!', 'eventful' ),
						'<b>',
						'</b>'
					) . '</p>';

					echo '<ul>';
					echo '<li><i class="icofont-verification-check"></i> ' . esc_html__( '10+ Beautiful Layouts', 'eventful' ) . '</li>';
					echo '<li><i class="icofont-verification-check"></i> ' . esc_html__( 'Events View & Filter All Types', 'eventful' ) . '</li>';
					echo '<li><i class="icofont-verification-check"></i> ' . esc_html__( 'Ajax Live Filtering & Search', 'eventful' ) . '</li>';
					echo '<li><i class="icofont-verification-check"></i> ' . esc_html__( 'Filter Events By Date & Time', 'eventful' ) . '</li>';
					echo '<li><i class="icofont-verification-check"></i> ' . esc_html__( '13+ Social Share Platforms', 'eventful' ) . '</li>';
					echo '<li><i class="icofont-verification-check"></i> ' . esc_html__( 'Custom Fields support', 'eventful' ) . '</li>';
					echo '<li><i class="icofont-verification-check"></i> ' . esc_html__( 'Ajax Load More & Infinite Scroll', 'eventful' ) . '</li>';
					echo '<li><i class="icofont-verification-check"></i> ' . esc_html__( 'Customize Details Page in Popup', 'eventful' ) . '</li>';
					echo '<li><i class="icofont-verification-check"></i> ' . esc_html__( '100+ Customizations & More', 'eventful' ) . '</li>';
					echo '</ul>';

					echo '<div class="eventful-notice-button">';
					echo '<a class="eventful-open-live-demo" href="https://wpeventful.com/pricing/?ref=1" target="_blank">';
					echo esc_html__( 'Upgrade to Pro Now', 'eventful' ) . ' <i class="icofont-rocket-alt-2"></i>';
					echo '</a>';
					echo '</div>';
					echo '</div>';
				}
			} else {
				echo ( !empty( $post_id ) ) ? '<div class="eventful-scode-wrap-side"><p>Eventful has seamless integration with Gutenberg, Classic Editor, <strong>Elementor,</strong> Divi, Bricks, Beaver, Oxygen, WPBakery Builder, etc.</p></div>' : '';
			}
		}

	}
}

<?php

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulLoopHtml;

/**
 * Pagination display provider
 *
 * This template can be overridden by copying it to yourtheme/eventful/templates/pagination.php
 *
 * @package    Eventful
 * @subpackage Eventful/public
 */

if (! defined('ABSPATH')) {
	exit;
}

if ($show_pagination) {
	// Paged argument.
	if (get_query_var('paged')) {
		$eventful_paged = get_query_var('paged');
	} elseif (get_query_var('page')) {
		$eventful_paged = get_query_var('page');
	} else {
		$eventful_paged = 1;
	}
	$eventful_setting_options        = get_option( 'eventful_settings' );
	$eventful_load_more_button_color = isset( $eventful_setting_options['load_more_button_color'] ) ? $eventful_setting_options['load_more_button_color'] : array();
	$eventful_load_more_text_color   = isset( $eventful_load_more_button_color['text_color'] ) ? $eventful_load_more_button_color['text_color'] : '#5e5e5e';
	$eventful_load_more_text_hover   = isset( $eventful_load_more_button_color['text_hover'] ) ? $eventful_load_more_button_color['text_hover'] : '#ffffff';
	$eventful_load_more_border_color = isset( $eventful_load_more_button_color['border_color'] ) ? $eventful_load_more_button_color['border_color'] : '#bbbbbb';
	$eventful_load_more_border_hover = isset( $eventful_load_more_button_color['border_hover'] ) ? $eventful_load_more_button_color['border_hover'] : '#222222';
	$eventful_load_more_background   = isset( $eventful_load_more_button_color['background'] ) ? $eventful_load_more_button_color['background'] : '#ffffff';
	$eventful_load_more_active_bg    = isset( $eventful_load_more_button_color['active_background'] ) ? $eventful_load_more_button_color['active_background'] : '#222222';
	$eventful_preloader_color        = isset( $eventful_load_more_button_color['preloader_color'] ) ? $eventful_load_more_button_color['preloader_color'] : '#222222';

	$eventful_load_more_button_text    = isset( $options['load_more_button_text'] ) ? $options['load_more_button_text'] : 'Load More';
	$eventful_load_more_ending_message = isset( $options['load_more_ending_message'] ) ? $options['load_more_ending_message'] : 'No more events available';
	$eventful_pagination_alignment     = isset( $options['pagination_alignment'] ) ? $options['pagination_alignment'] : 'left';
	$eventful_loadmore_btn_color       = isset( $options['eventful_loadmore_btn_color'] ) ? $options['eventful_loadmore_btn_color'] : array();
	$eventful_load_more_text_color     = ! empty( $eventful_loadmore_btn_color['text_color'] ) ? $eventful_loadmore_btn_color['text_color'] : $eventful_load_more_text_color;
	$eventful_load_more_text_hcolor    = ! empty( $eventful_loadmore_btn_color['text_hcolor'] ) ? $eventful_loadmore_btn_color['text_hcolor'] : $eventful_load_more_text_hover;
	$eventful_load_more_border_color   = ! empty( $eventful_loadmore_btn_color['border_color'] ) ? $eventful_loadmore_btn_color['border_color'] : $eventful_load_more_border_color;
	$eventful_load_more_border_hcolor  = ! empty( $eventful_loadmore_btn_color['border_hcolor'] ) ? $eventful_loadmore_btn_color['border_hcolor'] : $eventful_load_more_border_hover;
	$eventful_load_more_background     = ! empty( $eventful_loadmore_btn_color['background'] ) ? $eventful_loadmore_btn_color['background'] : $eventful_load_more_background;
	$eventful_load_more_active_bg      = ! empty( $eventful_loadmore_btn_color['active_background'] ) ? $eventful_loadmore_btn_color['active_background'] : $eventful_load_more_active_bg;

	echo '<div class="ta-row">
		<div class="ta-col-xs-1 eventful_py_0">
		<div style="
			--load_more_text_color: ' . esc_attr( $eventful_load_more_text_color ) . ';
			--load_more_text_hcolor: ' . esc_attr( $eventful_load_more_text_hcolor ) . ';
			--load_more_border_color: ' . esc_attr( $eventful_load_more_border_color ) . ';
			--load_more_border_hcolor: ' . esc_attr( $eventful_load_more_border_hcolor ) . ';
			--load_more_background: ' . esc_attr( $eventful_load_more_background ) . ';
			--load_more_active_background: ' . esc_attr( $eventful_load_more_active_bg ) . ';
			--pagination_alignment: ' . esc_attr( $eventful_pagination_alignment ) . ';
		">';
?>
	<span class="ta-eventful-pagination-data" style="display:none;" data-loadmoretext="<?php echo esc_attr( $eventful_load_more_button_text ); ?>" data-endingtext="<?php echo esc_attr( $eventful_load_more_ending_message ); ?>"></span>

	<nav class="eventful__event_pagination eventful-on-desktop <?php echo esc_attr($pagination_type); ?>">
		<?php EventfulLoopHtml::eventful_pagination_bar($events_found, $options, $layout, $eventful_gl_id, $eventful_paged); ?>
	</nav>
	<?php if ('filter_layout' !== $layout_preset) { ?>
		<nav class="eventful__event_pagination eventful-on-mobile <?php echo esc_attr($pagination_type_mobile); ?>">
			<?php EventfulLoopHtml::eventful_pagination_bar($events_found, $options, $layout, $eventful_gl_id, $eventful_paged, 'on_mobile'); ?>
		</nav>

<?php
	}
	echo '</div>		
		</div>
		</div>';
}
?>
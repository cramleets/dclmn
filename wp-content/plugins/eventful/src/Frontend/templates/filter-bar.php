<?php

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulLiveFilter;

/**
 *  Shuffle filter bar file
 *
 * This template can be overridden by copying it to yourtheme/eventful/templates/filter-bar.php
 *
 * @package    Eventful
 * @subpackage Eventful/public/template
 */

if (is_array($advanced_filter)) {
	ob_start();
	EventfulLiveFilter::eventful_live_filter_options($options, $query_args, $eventful_gl_id);
	$filter_bar = ob_get_clean();

	ob_start();
	EventfulLiveFilter::eventful_live_search_bar($options, $eventful_gl_id);
	$ex_filter_bar = ob_get_clean();

	$eventful_advanced_filter = isset($options['eventful_advanced_filter']) ? $options['eventful_advanced_filter'] : '';
	$show_hide_filter_button = isset($options['show_hide_filter_button']) ? $options['show_hide_filter_button'] : '';
	$eventful_filter_by_keyword = isset($options['eventful_filter_by_keyword']) ? $options['eventful_filter_by_keyword'] : '';
	$add_search_filter_post = isset($eventful_filter_by_keyword['add_search_filter_post']) ? $eventful_filter_by_keyword['add_search_filter_post'] : '';
	$advance_filter_wrapper_margin = isset($options['advance_filter_wrapper_margin']) ? $options['advance_filter_wrapper_margin'] : '';
	$margin_bottom = isset($advance_filter_wrapper_margin['bottom']) ? $advance_filter_wrapper_margin['bottom'] : '';
	$margin_unit = isset($advance_filter_wrapper_margin['unit']) ? $advance_filter_wrapper_margin['unit'] : '';
	$wrapper_margin = $margin_bottom . $margin_unit;

	$eventful_filter_by_keyword = isset($options['eventful_filter_by_keyword']) ? $options['eventful_filter_by_keyword'] : '';
	$add_filter_post       = isset($eventful_filter_by_keyword['add_search_filter_post']) ? $eventful_filter_by_keyword['add_search_filter_post'] : '';

	$ajax_filter_options   = isset($eventful_filter_by_keyword['ajax_filter_options']) ? $eventful_filter_by_keyword['ajax_filter_options'] : '';
	$search_bar_width   = !empty($ajax_filter_options['search_bar_width']['width']) ? $ajax_filter_options['search_bar_width']['width'] : '';
	$search_br_unit   = isset($ajax_filter_options['search_bar_width']['unit']) ? $ajax_filter_options['search_bar_width']['unit'] : '';
	if ($search_bar_width  == '') {
		$search_bar_width = '100%';
	} else {
		$search_bar_width = $search_bar_width . $search_br_unit;
	}
	$live_filter_align   = !empty($ajax_filter_options['eventful_live_filter_align']) ? $ajax_filter_options['eventful_live_filter_align'] : 'center';

	$keyword = isset($eventful_advanced_filter[0]) ? $eventful_advanced_filter[0] : '';
	$filter_option = isset($eventful_advanced_filter[1]) ? $eventful_advanced_filter[1] : '';

	$show_hide_button = '';
	if ($keyword && $filter_option && $show_hide_filter_button && $add_filter_post) {
		if ($add_search_filter_post) {
			$show_hide_button = 'activate_show_hide_button';
		}
	}

	$advance_filter_reset_button = isset($options['advance_filter_reset_button']) ? $options['advance_filter_reset_button'] : true;
	if (! empty($ex_filter_bar) || ! empty($filter_bar)) {
		echo '<div class="eventful_filter_wrapper ' . esc_attr($show_hide_button) . '" style="--wrapper_margin: ' . esc_attr($wrapper_margin) . ';--search_bar_width: '. esc_attr($search_bar_width) .';">';

		if (! empty($ex_filter_bar)) { ?>
			<div class="eventful_ex_filter_bar <?php echo esc_attr('align_' . $live_filter_align); ?>">
				<?php $allowed_tags = array(
					'div'   => array('class' => array()),
					'i'     => array('class' => array()),
					'input' => array(
						'id'          => array(),
						'type'        => array(),
						'value'       => array(),
						'class'       => array(),
						'placeholder' => array(),
					),
					'button' => array('class' => array(),'style' => array(),'data-show_button' => array(),'data-hide_button' => array(),'type' => array(),),
				);
				echo wp_kses($ex_filter_bar, $allowed_tags); ?>

			</div>
		<?php }
		if (! empty($filter_bar)) {
			$eventful_filter_options = isset($options['eventful_filter_options']) ? $options['eventful_filter_options'] : '';
			$margin_between_filter = isset($eventful_filter_options['margin_between_filter']['all']) ? $eventful_filter_options['margin_between_filter']['all'] : 20;
			$margin_between_filter_half = $margin_between_filter / 2;
		?>
			<div class="eventful__filter_bar ta-row" style="--margin_between_event: <?php echo esc_attr( $margin_between_filter ); ?>px;--margin_between_event_half: <?php echo esc_attr( $margin_between_filter_half ); ?>px;">
				<?php $allowed_tags = array(
					'div'   => array('class' => array(), 'style' => array(), 'data-taxonomy' => array()),
					'i'     => array('class' => array()),
					'form'  => array('class' => array(), 'style' => array(), 'data-taxonomy' => array()),
					'select' => array('id' => array(), 'data-taxonomy' => array()),
					'option' => array('value' => array(), 'data-taxonomy' => array()),
					'input' => array(
						'type' => array(),
						'name' => array(),
						'value' => array(),
						'checked' => array(),
						'data-taxonomy' => array(),
					),
					'label' => array(),
					'span'  => array('class' => array()),
					'p'     => array(),
				);

				echo wp_kses($filter_bar, $allowed_tags); ?>
			</div>
<?php }
		if ($advance_filter_reset_button) {
			echo '<div class="search_filter"><button class="reset_search_filter"><i class="icofont-ui-reply"></i> Reset</button></div>';
		}
		echo '</div>';
	}
} ?>
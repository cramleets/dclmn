<?php

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;

/**
 * Item thumbnail
 *
 * This template can be overridden by copying it to yourtheme/eventful/templates/item/thumbnail.php
 *
 * @package    Eventful
 * @subpackage Eventful/public
 */

$_event_thumb_setting    = EventfulFunctions::eventful_metabox_value('eventful_event_thumb', $sorter);
$eventful_link_rel       = EventfulFunctions::eventful_metabox_value('eventful_link_rel', $sorter);
$eventful_page_link_type = EventfulFunctions::eventful_metabox_value('eventful_page_link_type', $options);

$lazy_load = isset($_event_thumb_setting['eventful_img_lazy_load'])
	? $_event_thumb_setting['eventful_img_lazy_load']
	: true;
$lazy_load = apply_filters('eventful_img_lazy_load', $lazy_load);
$event_thumb_margin        = isset($_event_thumb_setting['event_thumb_margin']) ? $_event_thumb_setting['event_thumb_margin'] : array();
$event_thumb_margin_top    = ! empty($event_thumb_margin['top']) ? $event_thumb_margin['top'] : '0';
$event_thumb_margin_right = ! empty($event_thumb_margin['right']) ? $event_thumb_margin['right'] : '0';
$event_thumb_margin_bottom = ! empty($event_thumb_margin['bottom']) ? $event_thumb_margin['bottom'] : '0';
$event_thumb_margin_left  = ! empty($event_thumb_margin['left']) ? $event_thumb_margin['left'] : '0';
$event_thumb_margin       = "{$event_thumb_margin_top}px {$event_thumb_margin_right}px {$event_thumb_margin_bottom}px {$event_thumb_margin_left}px;";
$event_thumb_border_radius        = isset($_event_thumb_setting['event_thumb_border_radius']) ? $_event_thumb_setting['event_thumb_border_radius'] : array();
$event_thumb_border_radius_top    = ! empty($event_thumb_border_radius['top']) ? $event_thumb_border_radius['top'] : '0';
$event_thumb_border_radius_right  = ! empty($event_thumb_border_radius['right']) ? $event_thumb_border_radius['right'] : '0';
$event_thumb_border_radius_bottom = ! empty($event_thumb_border_radius['bottom']) ? $event_thumb_border_radius['bottom'] : '0';
$event_thumb_border_radius_left   = ! empty($event_thumb_border_radius['left']) ? $event_thumb_border_radius['left'] : '0';
$event_thumb_border_radius_unit   = ! empty($event_thumb_border_radius['unit']) ? $event_thumb_border_radius['unit'] : 'px';
$event_thumb_border_radius        = "{$event_thumb_border_radius_top}{$event_thumb_border_radius_unit} {$event_thumb_border_radius_right}{$event_thumb_border_radius_unit} {$event_thumb_border_radius_bottom}{$event_thumb_border_radius_unit} {$event_thumb_border_radius_left}{$event_thumb_border_radius_unit}";
$event_thumb_border = isset($_event_thumb_setting['event_thumb_border']) ? $_event_thumb_setting['event_thumb_border'] : array();
$border_all         = isset($event_thumb_border['all']) ? $event_thumb_border['all'] : '';
$border_style       = isset($event_thumb_border['style']) ? $event_thumb_border['style'] : 'solid';
$border_color       = isset($event_thumb_border['color']) ? $event_thumb_border['color'] : 'transparent';
$event_thumb_border = "{$border_all}px {$border_style} {$border_color};";
$thumbnail_background = isset($_event_thumb_setting['thumbnail_background']) ? $_event_thumb_setting['thumbnail_background'] : '';
$eventful_lazy_load   = isset($options['eventful_lazy_load']) ? $options['eventful_lazy_load'] : '';
$g_option = get_option('eventful_settings');
$carousel_preloader_color = isset($g_option['carousel_preloader_color']) ? $g_option['carousel_preloader_color'] : '';
$preloader_color      = isset($options['preloader_color']) ? $options['preloader_color'] : $carousel_preloader_color;
$eventful_link_rel_text = ('1' === $eventful_link_rel) ? "rel='nofollow'" : '';
$eventful_link_target   = EventfulFunctions::eventful_metabox_value('eventful_link_target', $options);
if (EventfulFunctions::eventful_metabox_value('event_thumb_show', $_event_thumb_setting)) :
	$eventful_image_attr = EventfulFunctions::eventful_sized_thumb(
		$_event_thumb_setting,
		$event->ID,
		$layout
	);
	$thumb_url = $eventful_image_attr['src'];
	if (! empty($thumb_url)) :

		$retina_img_src  = $eventful_image_attr['2x_src'];
		$retina_img_attr = ! empty($retina_img_src)
			? 'srcset="' . esc_attr($thumb_url) . ', ' . esc_attr($retina_img_src) . ' 2x"'
			: '';

		$alter_text = EventfulFunctions::eventful_thumb_alter_text($event->ID);

		if (('grid_layout' === $layout || 'masonry_layout' === $layout || 'list_layout' === $layout || 'minimal_list' === $layout) && $lazy_load && ! is_admin()) {
			wp_enqueue_script('eventful-lazy');
			$image = sprintf(
				'<img data-eventful_src="%1$s" %5$s class="eventful-lazyload" width="%2$s" height="%3$s" alt="%4$s">',
				esc_url($thumb_url),
				esc_attr($eventful_image_attr['width']),
				esc_attr($eventful_image_attr['height']),
				esc_attr($alter_text),
				$retina_img_attr
			);
		} else {
			$lazy_loading = $eventful_lazy_load ? 'loading="lazy"' : '';
			$image = sprintf(
				'<img %6$s %5$s src="%1$s" width="%2$s" height="%3$s" alt="%4$s">',
				esc_url($thumb_url),
				esc_attr($eventful_image_attr['width']),
				esc_attr($eventful_image_attr['height']),
				esc_attr($alter_text),
				$retina_img_attr,
				wp_kses_post($lazy_loading)
			);
		}
?>
		<div class="eventful__item--thumbnail"
			style="
				position:relative;
				--eventful-thumb-background: <?php echo esc_attr($thumbnail_background); ?>;
				--event_thumb_margin: <?php echo esc_attr($event_thumb_margin); ?>;
				--event_thumb_border: <?php echo esc_attr($event_thumb_border); ?>;
				--event_thumb_border_radius: <?php echo esc_attr($event_thumb_border_radius); ?>;
				--preloader_color: <?php echo esc_attr($preloader_color); ?>;
			">

			<?php if ($eventful_lazy_load && in_array($layout, array('carousel_layout', 'slider'), true) && 'ticker' !== $layout) : ?>
				<div class="swiper-lazy-preloader"
					style="--swiper-preloader-color: <?php echo esc_attr($preloader_color); ?>"></div>
			<?php endif; ?>

			<?php $tag = ('none' === $eventful_page_link_type) ? 'span' : 'a'; ?>

			<<?php echo esc_html($tag); ?>
				class="ta-eventful-thumb"
				<?php if ('a' === $tag) : ?>
				href="<?php echo esc_url(get_permalink($event)); ?>"
				target="<?php echo esc_attr($eventful_link_target); ?>"
				<?php endif; ?>
				<?php echo esc_attr($eventful_link_rel_text); ?>>

				<?php
				echo wp_kses($image, [
					'img' => [
						'src' => true,
						'srcset' => true,
						'alt' => true,
						'width' => true,
						'height' => true,
						'class' => true,
						'style' => true,
						'data-eventful_src' => true,
						'loading' => true,
					],
				]);
				?>
			</<?php echo esc_html($tag); ?>>

		</div>

	<?php endif; ?>
<?php endif; ?>
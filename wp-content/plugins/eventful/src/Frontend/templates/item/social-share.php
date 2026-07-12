<?php

/**
 * Social share
 *
 * This template can be overridden by copying it to yourtheme/eventful/templates/item/social-share.php
 *
 * @package    Eventful
 * @subpackage Eventful/public
 */

use ThemeAtelier\Eventful\Frontend\Helpers\EventfulFunctions;

$template_style   = isset($options['template_style']) ? $options['template_style'] : 'custom';
$eventful_event_social_share   = EventfulFunctions::eventful_metabox_value('eventful_event_social_share', $sorter);
$social_share_media   = EventfulFunctions::eventful_metabox_value('social_sharing_media', $eventful_event_social_share);
$social_icon_shape    = EventfulFunctions::eventful_metabox_value('social_icon_shape', $eventful_event_social_share);
$show_social_media    = EventfulFunctions::eventful_metabox_value('show_social_media', $eventful_event_social_share);

$event_social_share_margin = EventfulFunctions::eventful_metabox_value('social_margin', $eventful_event_social_share);
$event_social_share_margin_top = EventfulFunctions::eventful_metabox_value('top', $event_social_share_margin);
$event_social_share_margin_right = EventfulFunctions::eventful_metabox_value('right', $event_social_share_margin);
$event_social_share_margin_bottom = EventfulFunctions::eventful_metabox_value('bottom', $event_social_share_margin);
$event_social_share_margin_left = EventfulFunctions::eventful_metabox_value('left', $event_social_share_margin);
if ('custom' === $template_style) {
	$event_social_share_margin = "{$event_social_share_margin_top}px {$event_social_share_margin_right}px {$event_social_share_margin_bottom}px {$event_social_share_margin_left}px;";
} else {
	$event_social_share_margin = '';
}

$social_icon_custom_color = EventfulFunctions::eventful_metabox_value('social_icon_custom_color', $eventful_event_social_share);
$social_position = EventfulFunctions::eventful_metabox_value('social_position', $eventful_event_social_share);

if ($show_social_media && !empty($social_share_media) && is_array($social_share_media)) {
	echo '<div class="eventful__item__social-share"
style="
--event_social_share_margin: ' . esc_attr($event_social_share_margin) . ';
--social_position: ' . esc_attr($social_position) . ';
"
>';
	do_action('eventful_add_first_socials');
	foreach ($social_share_media as $style_key => $style_value) {
		switch ($style_value) {
			case 'facebook':
?>
				<a title="<?php echo esc_attr('Facebook', 'eventful') ?>" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url(get_the_permalink($event)); ?>" class="eventful-social-icon eventful-facebook <?php echo esc_attr($social_icon_shape); ?>" onClick="window.open('https://www.facebook.com/sharer.php?u=<?php echo esc_url(get_the_permalink($event)); ?>','Facebook','width=450,height=300,left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+''); return false;"><i class="icofont-facebook"></i></a>
			<?php
				break;
			case 'twitter':
			?>
				<a title="<?php echo esc_attr('Twitter', 'eventful') ?>" onClick="window.open('https://twitter.com/share?url=<?php echo esc_url(get_the_permalink($event)); ?>&amp;text=<?php echo esc_attr(get_the_title($event)); ?>','Twitter share','width=450,height=300,left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+''); return false;" href="https://twitter.com/share?url=<?php echo esc_url(get_the_permalink($event)); ?>&amp;text=<?php echo esc_attr(get_the_title($event)); ?>" class="eventful-social-icon eventful-twitter <?php echo esc_attr($social_icon_shape); ?>"> <i class="icofont-twitter"></i></a>
			<?php
				break;
			case 'linkedIn':
			?>
				<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo esc_url(get_the_permalink($event)); ?>" title="<?php echo esc_attr('linkedIn', 'eventful') ?>" class="eventful-social-icon eventful-linkedin <?php echo esc_attr($social_icon_shape); ?>" onClick="window.open('https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo esc_url(get_the_permalink($event)); ?>','Linkedin','width=450,height=300,left='+(screen.availWidth/2-431)+',top='+(screen.availHeight/2-250)+''); return false;" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo esc_url(get_the_permalink($event)); ?>"> <i class="icofont-linkedin"></i></a>
			<?php
				break;
			case 'pinterest':
			?>
				<a href='javascript:void((function()%7Bvar%20e=document.createElement(&apos;script&apos;);e.setAttribute(&apos;type&apos;,&apos;text/javascript&apos;);e.setAttribute(&apos;charset&apos;,&apos;UTF-8&apos;);e.setAttribute(&apos;src&apos;,&apos;https://assets.pinterest.com/js/pinmarklet.js?r=&apos;+Math.random()*99999999);document.body.appendChild(e)%7D)());' class="eventful-social-icon eventful-pinterest <?php echo esc_attr($social_icon_shape); ?>" title="<?php echo esc_attr('Pinterest', 'eventful') ?>"> <i class="icofont-pinterest"></i></a>
			<?php
				break;
			case 'email':
			?>
				<a href="mailto:?Subject=<?php echo esc_attr(get_the_title($event)); ?>&amp;Body=<?php echo esc_url(get_the_permalink($event)); ?>" title="<?php echo esc_attr('Email', 'eventful') ?>" class="eventful-social-icon eventful-envelope <?php echo esc_attr($social_icon_shape); ?>"> <i class="icofont-envelope"></i></a>
			<?php
				break;
			case 'instagram':
			?>
				<a title="<?php echo esc_attr('Instagram', 'eventful') ?>" onClick="window.open('https://instagram.com/?url=<?php echo esc_url(get_the_permalink($event)); ?>&amp;text=<?php echo esc_attr(get_the_title($event)); ?>','Twitter share','width=450,height=300,left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+''); return false;" href="https://instagram.com/?url=<?php echo esc_url(get_the_permalink($event)); ?>&amp;text=<?php echo esc_attr(get_the_title($event)); ?>" class="eventful-social-icon eventful-instagram <?php echo esc_attr($social_icon_shape); ?>"> <i class="icofont-instagram" aria-hidden="true"></i></a>
			<?php
				break;
			case 'whatsapp':
			?>
				<a href="https://api.whatsapp.com/send?text=<?php echo esc_attr(get_the_title($event)); ?>%20<?php echo esc_url(get_the_permalink($event)); ?>" onClick="window.open('https://api.whatsapp.com/send?text=<?php echo esc_attr(get_the_title($event)); ?>%20<?php echo esc_url(get_the_permalink($event)); ?>','whatsapp','width=450,height=300,left='+(screen.availWidth/2-431)+',top='+(screen.availHeight/2-250)+''); return false;" title="<?php echo esc_attr('WhatsApp', 'eventful') ?>" class="eventful-social-icon eventful-whatsapp <?php echo esc_attr($social_icon_shape); ?>"> <i class="icofont-brand-whatsapp"></i></a>
			<?php
				break;
			case 'reddit':
			?>
				<a href="https://reddit.com/submit?url=<?php echo esc_url(get_the_permalink($event)); ?>&amp;title=<?php echo esc_attr(get_the_title($event)); ?>" onClick="window.open('https://reddit.com/submit?url=<?php echo esc_url(get_the_permalink($event)); ?>&amp;title=<?php echo esc_attr(get_the_title($event)); ?>','reddit','width=450,height=300,left='+(screen.availWidth/2-431)+',top='+(screen.availHeight/2-250)+''); return false;" title="<?php echo esc_attr('Reddit', 'eventful') ?>" class="eventful-social-icon eventful-reddit <?php echo esc_attr($social_icon_shape); ?>"> <i class="icofont-reddit"></i></a>
			<?php
				break;
			case 'tumblr':
			?>
				<a href="https://www.tumblr.com/widgets/share/tool?canonicalUrl=<?php echo esc_url(get_the_permalink($event)); ?>&amp;title=<?php echo esc_attr(get_the_title($event)); ?>" title="<?php echo esc_attr('tumblr', 'eventful') ?>" onClick="window.open('https://www.tumblr.com/widgets/share/tool?canonicalUrl=<?php echo esc_url(get_the_permalink($event)); ?>&amp;title=<?php echo esc_attr(get_the_title($event)); ?>','tumblr','width=450,height=300,left='+(screen.availWidth/2-431)+',top='+(screen.availHeight/2-250)+''); return false;" class="eventful-social-icon eventful-tumblr <?php echo esc_attr($social_icon_shape); ?>"><i class="icofont-tumblr"></i></a>
			<?php
				break;
			case 'digg':
			?>
				<a href="https://digg.com/submit?url=<?php echo esc_url(get_the_permalink($event)); ?>%&amp;title=<?php echo esc_attr(get_the_title($event)); ?>" onClick="window.open('https://digg.com/submit?url=<?php echo esc_url(get_the_permalink($event)); ?>%&amp;title=<?php echo esc_attr(get_the_title($event)); ?>','Digg','width=450,height=300,left='+(screen.availWidth/2-431)+',top='+(screen.availHeight/2-250)+''); return false;" title="<?php echo esc_html('digg', 'eventful') ?>" class="eventful-social-icon eventful-digg <?php echo esc_attr($social_icon_shape); ?>"><i class="icofont-digg"></i></a>
			<?php
				break;
			case 'vk':
			?>
				<a href="https://vk.com/share.php?url=<?php echo esc_url(get_the_permalink($event)); ?>&amp;title=<?php echo esc_attr(get_the_title($event)); ?>&amp;comment=" title="<?php echo esc_attr('VK', 'eventful') ?>" onClick="window.open('https://vk.com/share.php','VK','width=450,height=300,left='+(screen.availWidth/2-431)+',top='+(screen.availHeight/2-250)+''); return false;" class="eventful-social-icon eventful-vk <?php echo esc_attr($social_icon_shape); ?>"> <i class="icofont-vk"></i></a>
			<?php
				break;
			case 'xing':
			?>
				<a href="https://www.xing.com/spi/shares/new?url=<?php echo esc_url(get_the_permalink($event)); ?>" onClick="window.open('https://www.xing.com/spi/shares/new?url=<?php echo esc_url(get_the_permalink($event)); ?>','xing','width=450,height=300,left='+(screen.availWidth/2-431)+',top='+(screen.availHeight/2-250)+''); return false;" title="<?php echo esc_attr('Xing', 'eventful') ?>" class="eventful-social-icon eventful-xing <?php echo esc_attr($social_icon_shape); ?>"><i class="icofont-xing"></i></a>
			<?php
				break;
			case 'pocket':
			?>
				<a href="https://getpocket.com/edit?url=<?php echo esc_url(get_the_permalink($event)); ?>" onClick="window.open('https://getpocket.com/edit?url=<?php echo esc_url(get_the_permalink($event)); ?>','ocket','width=450,height=300,left='+(screen.availWidth/2-431)+',top='+(screen.availHeight/2-250)+''); return false;" title="<?php echo esc_attr('Pocket', 'eventful') ?>" class="eventful-social-icon eventful-pocket <?php echo esc_attr($social_icon_shape); ?>"> <i class="icofont-brand-target"></i></a>
	<?php
				break;
		}
	}
	do_action('eventful_add_last_socials');
	?>
	</div>
<?php }

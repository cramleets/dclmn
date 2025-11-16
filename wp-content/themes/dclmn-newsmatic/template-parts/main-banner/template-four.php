<?php

/**
 * Main Banner template four
 * 
 * @package Newsmatic
 * @since 1.0.0
 */

use Newsmatic\CustomizerDefault as ND;
use Tribe__Date_Utils as Dates;

$slider_args = $args['slider_args'];
$args = ['category' => 'featured'];
$posts2 = get_recent_posts_and_events($args);
$first_reads = array_slice($posts2['posts'], 0, 2) + $posts2['events'];
$second_reads = array_slice($posts2['events'], 0, 4);

if (!$first_reads && !$second_reads) return;
?>
<div class="main-banner-wrap">
    <div class="main-banner-slider" data-auto="true" data-arrows="true">
        <?php
        if (!empty($first_reads)) :
            foreach ($first_reads as $post) :
                $is_event = strstr($post->post_type, 'tribe_event');
                if ($is_event) {
                    $event_thumbs = $post->thumbnail->fetch_data();
                    $event_thumb = $event_thumbs['full'] ?? false;

                    $post_thumb = $event_thumb->url ?? false;

                    $display_date = empty($is_past) && ! empty($request_date)
                        ? max($post->dates->start_display, $request_date)
                        : $post->dates->start_display;

                    $event_week_day  = $display_date->format_i18n('l');
                    $event_day_num   = $display_date->format_i18n('j');
                    $event_month   = $display_date->format_i18n('F');
                    $event_date_attr = $display_date->format(Dates::DBDATEFORMAT);

                    if ($post->multiday) {
                        // The date returned back contains HTML and is already escaped.
                        $event_time = $post->schedule_details->value();
                    } elseif ($post->all_day) {
                        $event_time = esc_html_x('All day', 'All day label for event', 'the-events-calendar');
                    } else {
                        // The date returned back contains HTML and is already escaped.
                        $event_time = $post->short_schedule_details->value();
                    }
                } else {
                    $post_thumb = get_the_post_thumbnail_url($post->ID);
                }

                $attr_title = esc_attr(strip_tags($post->title));
                $post->title = ($post->title) ?: $post->post_title;
                //$post_thumb = str_replace('local.', '', $post_thumb);

                $excerpt = get_the_excerpt($post->ID);

        ?>
                <article class="slide-item <?php if (empty($post_thumb)) echo esc_attr('no-feat-img') ?>">
                    <figure class="post-thumb">
                        <a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $attr_title; ?>"><img src="<?php echo $post_thumb ?>"></a>
                    </figure>
                    <div class="post-element">
                        <h2 class="post-title">
                            <div class="post-element">
                                <?php
                                if ($is_event) {
                                    $out = '';
                                    $out .= '<span class="event-week-day">' . $event_week_day . ', </span> ';
                                    $out .= '<span class="event-month">' . $event_month . '</span> ';
                                    $out .= '<span class="event-date">' . $event_day_num . '</span> ';
                                    $out .= '<span class="event-time"> ' . strip_tags($event_time) . '</span>';
                                    echo '<span class="event-date-info">' . $out . '</span>';
                                }
                                ?>
                                <a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $attr_title; ?>"><?php echo $post->title ?></a>
                            </div>
                        </h2>
                        <div class="post-excerpt"><?php echo $excerpt; ?></div>
                    </div>
                </article>
        <?php
            endforeach;
        endif;
        ?>
    </div>
</div>

<div class="main-banner-block-posts banner-trailing-posts">
    <?php
    if (!empty($second_reads)) :
        foreach ($second_reads as $post) :
            $event_thumbs = $post->thumbnail->fetch_data();
            $event_thumb = $event_thumbs['full'] ?? false;

            $post_thumb = $event_thumb->url ?? false;
            $post_thumb = str_replace('local.', '', $post_thumb);

            $post->title = ($post->title) ?: $post->post_title;
            $attr_title = esc_attr(strip_tags($post->title));

            $excerpt = get_the_excerpt($post->ID);


            $display_date = empty($is_past) && ! empty($request_date)
                ? max($post->dates->start_display, $request_date)
                : $post->dates->start_display;

            $event_week_day  = $display_date->format_i18n('D');
            $event_day_num   = $display_date->format_i18n('j');
            $event_month   = $display_date->format_i18n('M');
            $event_date_attr = $display_date->format(Dates::DBDATEFORMAT);

            if ($post->multiday) {
                // The date returned back contains HTML and is already escaped.
                $event_time = $post->schedule_details->value();
            } elseif ($post->all_day) {
                $event_time = esc_html_x('All day', 'All day label for event', 'the-events-calendar');
            } else {
                // The date returned back contains HTML and is already escaped.
                $event_time = $post->short_schedule_details->value();
            }

    ?>
            <article class="post-item <?php if (empty($post_thumb)) echo esc_attr('no-feat-img') ?>">
                <figure class="post-thumb">
                    <a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $attr_title; ?>"><img src="<?php echo $post_thumb ?>"></a>
                </figure>
                <h2 class="post-title">
                    <div class="post-element">
                        <?php
                        $out = '';
                        $out .= '<span class="event-week-day">' . $event_week_day . ', </span> ';
                        $out .= '<span class="event-month">' . $event_month . '</span> ';
                        $out .= '<span class="event-date">' . $event_day_num . '</span>, ';
                        $out .= '  ';
                        $out .= '<span class="event-time">' . $event_time . '</span>';
                        echo '<span class="event-date-info">' . $out . '</span>';
                        ?>
                        <a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $attr_title; ?>"><?php echo $post->title ?></a>
                    </div>
                </h2>

            </article>
    <?php
        endforeach;
    endif;
    ?>
</div>
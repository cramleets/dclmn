<?php
/**
 * Plugin Name: The Events Calendar - Duplicate Event
 * Description: Adds a Duplicate action to The Events Calendar events.
 * Version: 1.0.0
 * Author: Marc Steel/Chat GPT
 */

if (!defined('ABSPATH')) {
    exit;
}

class NAPCO_Duplicate_TEC_Event {

    private const ACTION = 'napco_duplicate_tec_event';

    public function __construct() {
        add_filter('post_row_actions', [$this, 'add_row_action'], 10, 2);
        add_action('admin_action_' . self::ACTION, [$this, 'duplicate_event']);
        add_action('admin_notices', [$this, 'admin_notice']);
    }

    /**
     * Add "Duplicate" to the Events list-table row actions.
     */
    public function add_row_action(array $actions, WP_Post $post): array {
        if (
            'tribe_events' !== $post->post_type ||
            !current_user_can('edit_post', $post->ID)
        ) {
            return $actions;
        }

        $url = wp_nonce_url(
            add_query_arg(
                [
                    'action'   => self::ACTION,
                    'event_id' => $post->ID,
                ],
                admin_url('admin.php')
            ),
            self::ACTION . '_' . $post->ID
        );

        $actions['napco_duplicate_tec_event'] = sprintf(
            '<a href="%s" aria-label="%s">%s</a>',
            esc_url($url),
            esc_attr(
                sprintf(
                    __('Duplicate “%s”', 'napco-duplicate-tec-event'),
                    $post->post_title
                )
            ),
            esc_html__('Duplicate', 'napco-duplicate-tec-event')
        );

        return $actions;
    }

    /**
     * Duplicate the requested event and redirect to its edit screen.
     */
    public function duplicate_event(): void {
        $event_id = isset($_GET['event_id'])
            ? absint($_GET['event_id'])
            : 0;

        if (!$event_id) {
            $this->redirect_with_error('missing_event');
        }

        check_admin_referer(self::ACTION . '_' . $event_id);

        $event = get_post($event_id);

        if (!$event || 'tribe_events' !== $event->post_type) {
            $this->redirect_with_error('invalid_event');
        }

        if (!current_user_can('edit_post', $event_id)) {
            wp_die(
                esc_html__('You are not allowed to duplicate this event.', 'napco-duplicate-tec-event'),
                esc_html__('Permission denied', 'napco-duplicate-tec-event'),
                ['response' => 403]
            );
        }

        if (!function_exists('tribe_events')) {
            $this->redirect_with_error('tec_unavailable');
        }

        /*
         * Recurring events are more complicated because TEC Pro stores
         * recurrence and occurrence information separately.
         *
         * Do not silently create a broken partial copy.
         */
        if ($this->is_recurring_event($event_id)) {
            $this->redirect_with_error('recurring_event');
        }

        $new_event = $this->create_event_copy($event);

        if (is_wp_error($new_event)) {
            $this->redirect_with_error(
                'create_failed',
                $new_event->get_error_message()
            );
        }

        if (!$new_event instanceof WP_Post) {
            $this->redirect_with_error('create_failed');
        }

        $this->copy_taxonomies($event_id, $new_event->ID);
        $this->copy_custom_meta($event_id, $new_event->ID);

        /**
         * Fires after an event has been duplicated.
         *
         * @param int $new_event_id    New event ID.
         * @param int $source_event_id Original event ID.
         */
        do_action(
            'napco_tec_event_duplicated',
            $new_event->ID,
            $event_id
        );

        clean_post_cache($new_event->ID);

        wp_safe_redirect(
            add_query_arg(
                [
                    'post'                     => $new_event->ID,
                    'action'                   => 'edit',
                    'napco_event_duplicated'   => 1,
                ],
                admin_url('post.php')
            )
        );

        exit;
    }

    /**
     * Create the core event through TEC's ORM.
     */
    private function create_event_copy(WP_Post $event) {
        $start_date = get_post_meta($event->ID, '_EventStartDate', true);
        $end_date   = get_post_meta($event->ID, '_EventEndDate', true);
        $timezone   = get_post_meta($event->ID, '_EventTimezone', true);

        if (!$start_date || !$end_date) {
            return new WP_Error(
                'missing_event_dates',
                __('The original event is missing its start or end date.', 'napco-duplicate-tec-event')
            );
        }

        $args = [
            'title'      => $event->post_title . ' (Copy)',
            'content'    => $event->post_content,
            'excerpt'    => $event->post_excerpt,
            'status'     => 'draft',
            'author'     => get_current_user_id(),
            'start_date' => $start_date,
            'end_date'   => $end_date,
        ];

        if ($timezone) {
            $args['timezone'] = $timezone;
        }

        $all_day = get_post_meta($event->ID, '_EventAllDay', true);

        if ('' !== $all_day) {
            $args['all_day'] = $this->is_truthy($all_day);
        }

        $venue_id = $this->get_first_meta_id(
            $event->ID,
            ['_EventVenueID', '_EventVenueID']
        );

        if ($venue_id) {
            $args['venue'] = $venue_id;
        }

        $organizer_ids = $this->get_meta_ids(
            $event->ID,
            ['_EventOrganizerID']
        );

        if ($organizer_ids) {
            /*
             * TEC installations have supported both a single organizer ID
             * and arrays of organizer IDs.
             */
            $args['organizer'] = 1 === count($organizer_ids)
                ? reset($organizer_ids)
                : $organizer_ids;
        }

        $image_id = get_post_thumbnail_id($event->ID);

        if ($image_id) {
            $args['image'] = $image_id;
        }

        $url = get_post_meta($event->ID, '_EventURL', true);

        if ($url) {
            $args['url'] = $url;
        }

        $cost = get_post_meta($event->ID, '_EventCost', true);

        if ('' !== $cost) {
            $args['cost'] = $cost;
        }

        $currency_symbol = get_post_meta(
            $event->ID,
            '_EventCurrencySymbol',
            true
        );

        if ('' !== $currency_symbol) {
            $args['currency_symbol'] = $currency_symbol;
        }

        $currency_position = get_post_meta(
            $event->ID,
            '_EventCurrencyPosition',
            true
        );

        if (in_array($currency_position, ['prefix', 'postfix'], true)) {
            $args['currency_position'] = $currency_position;
        }

        $show_map = get_post_meta($event->ID, '_EventShowMap', true);

        if ('' !== $show_map) {
            $args['show_map'] = $this->is_truthy($show_map);
        }

        $show_map_link = get_post_meta(
            $event->ID,
            '_EventShowMapLink',
            true
        );

        if ('' !== $show_map_link) {
            $args['show_map_link'] = $this->is_truthy($show_map_link);
        }

        $hide_from_upcoming = get_post_meta(
            $event->ID,
            '_EventHideFromUpcoming',
            true
        );

        if ('' !== $hide_from_upcoming) {
            $args['hide_from_upcoming'] = $this->is_truthy(
                $hide_from_upcoming
            );
        }

        $featured = get_post_meta(
            $event->ID,
            '_tribe_featured',
            true
        );

        if ('' !== $featured) {
            $args['featured'] = $this->is_truthy($featured);
        }

        /**
         * Filter the arguments sent to the TEC event ORM.
         *
         * @param array   $args  ORM creation arguments.
         * @param WP_Post $event Original event.
         */
        $args = apply_filters(
            'napco_duplicate_tec_event_args',
            $args,
            $event
        );

        try {
            $created = tribe_events()
                ->set_args($args)
                ->create();
        } catch (Throwable $throwable) {
            return new WP_Error(
                'tec_create_exception',
                $throwable->getMessage()
            );
        }

        if (!$created) {
            return new WP_Error(
                'tec_create_failed',
                __('The Events Calendar could not create the duplicate.', 'napco-duplicate-tec-event')
            );
        }

        return $created;
    }

    /**
     * Copy all registered taxonomies assigned to the event.
     */
    private function copy_taxonomies(int $source_id, int $new_id): void {
        $taxonomies = get_object_taxonomies('tribe_events');

        foreach ($taxonomies as $taxonomy) {
            $term_ids = wp_get_object_terms(
                $source_id,
                $taxonomy,
                ['fields' => 'ids']
            );

            if (is_wp_error($term_ids)) {
                continue;
            }

            wp_set_post_terms(
                $new_id,
                array_map('intval', $term_ids),
                $taxonomy,
                false
            );
        }
    }

    /**
     * Copy non-TEC custom metadata.
     *
     * TEC-managed event fields are excluded because the ORM has already
     * created them and synchronized the custom event tables.
     */
    private function copy_custom_meta(int $source_id, int $new_id): void {
        $all_meta = get_post_meta($source_id);

        $excluded_keys = $this->excluded_meta_keys();

        foreach ($all_meta as $meta_key => $values) {
            if (
                isset($excluded_keys[$meta_key]) ||
                str_starts_with($meta_key, '_Event') ||
                str_starts_with($meta_key, '_tribe')
            ) {
                continue;
            }

            foreach ($values as $value) {
                add_post_meta(
                    $new_id,
                    $meta_key,
                    maybe_unserialize($value)
                );
            }
        }
    }

    /**
     * Metadata that must not be carried to a new post.
     */
    private function excluded_meta_keys(): array {
        $keys = [
            '_edit_lock',
            '_edit_last',
            '_wp_old_slug',
            '_wp_attached_file',
            '_wp_attachment_metadata',
            '_thumbnail_id',
        ];

        /**
         * Filter metadata keys excluded from duplication.
         *
         * @param string[] $keys Excluded keys.
         */
        $keys = apply_filters(
            'napco_duplicate_tec_event_excluded_meta',
            $keys
        );

        return array_fill_keys($keys, true);
    }

    /**
     * Determine whether the source is a recurring event.
     */
    private function is_recurring_event(int $event_id): bool {
        if (
            function_exists('tribe_is_recurring_event') &&
            tribe_is_recurring_event($event_id)
        ) {
            return true;
        }

        $recurrence = get_post_meta(
            $event_id,
            '_EventRecurrence',
            true
        );

        return !empty($recurrence);
    }

    private function get_first_meta_id(
        int $post_id,
        array $keys
    ): int {
        $ids = $this->get_meta_ids($post_id, $keys);

        return $ids ? reset($ids) : 0;
    }

    /**
     * Normalize IDs from TEC meta that may be scalar, repeated, or arrays.
     */
    private function get_meta_ids(
        int $post_id,
        array $keys
    ): array {
        $ids = [];

        foreach ($keys as $key) {
            $values = get_post_meta($post_id, $key, false);

            foreach ($values as $value) {
                $value = maybe_unserialize($value);

                foreach ((array) $value as $candidate) {
                    $candidate = absint($candidate);

                    if ($candidate) {
                        $ids[] = $candidate;
                    }
                }
            }
        }

        return array_values(array_unique($ids));
    }

    private function is_truthy($value): bool {
        if (function_exists('tribe_is_truthy')) {
            return tribe_is_truthy($value);
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Redirect back to Events with an error code.
     */
    private function redirect_with_error(
        string $code,
        string $message = ''
    ): void {
        $args = [
            'post_type'             => 'tribe_events',
            'napco_duplicate_error' => sanitize_key($code),
        ];

        if ($message) {
            $args['napco_duplicate_message'] = rawurlencode($message);
        }

        wp_safe_redirect(
            add_query_arg(
                $args,
                admin_url('edit.php')
            )
        );

        exit;
    }

    /**
     * Display duplication success and error notices.
     */
    public function admin_notice(): void {
        if (!empty($_GET['napco_event_duplicated'])) {
            echo '<div class="notice notice-success is-dismissible"><p>';
            echo esc_html__(
                'Event duplicated. The new event is a draft.',
                'napco-duplicate-tec-event'
            );
            echo '</p></div>';

            return;
        }

        if (empty($_GET['napco_duplicate_error'])) {
            return;
        }

        $code = sanitize_key(
            wp_unslash($_GET['napco_duplicate_error'])
        );

        $messages = [
            'missing_event'   => __('No event was selected.', 'napco-duplicate-tec-event'),
            'invalid_event'   => __('The selected event could not be found.', 'napco-duplicate-tec-event'),
            'tec_unavailable' => __('The Events Calendar is not available.', 'napco-duplicate-tec-event'),
            'recurring_event' => __('Recurring events are not duplicated by this plugin because their recurrence and occurrence records require separate handling.', 'napco-duplicate-tec-event'),
            'create_failed'   => __('The event could not be duplicated.', 'napco-duplicate-tec-event'),
        ];

        $message = $messages[$code] ?? $messages['create_failed'];

        if (!empty($_GET['napco_duplicate_message'])) {
            $details = sanitize_text_field(
                rawurldecode(
                    wp_unslash($_GET['napco_duplicate_message'])
                )
            );

            if ($details) {
                $message .= ' ' . $details;
            }
        }

        echo '<div class="notice notice-error is-dismissible"><p>';
        echo esc_html($message);
        echo '</p></div>';
    }
}

new NAPCO_Duplicate_TEC_Event();
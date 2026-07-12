<?php

/**
 * Frontend engine for the "Replace Layout" settings page.
 *
 * Swaps The Events Calendar's default output for a chosen Eventful layout on the
 * events archive, category, tag and search pages, and the Related Events
 * section. Each page is controlled independently from the Replace Layout menu
 * (option `eventful_replace_layout`).
 *
 * The events views (archive/category/tag/search) are all produced by
 * `Template_Bootstrap::get_view_html()`, so a single filter swaps just that
 * region and leaves the surrounding page intact. Related Events is an Events
 * Calendar PRO section on the single page and is swapped via its own action.
 *
 * @link       https://themeatelier.net/
 * @since      2.3.0
 *
 * @package    Eventful
 * @subpackage Eventful/Includes
 */

namespace ThemeAtelier\Eventful\Includes;

// Don't call the file directly.
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Replace Layout frontend handler.
 *
 * @since 2.3.0
 */
class Eventful_Replace_Layout
{
    /**
     * Option key holding the Replace Layout settings.
     *
     * @var string
     */
    private $option_key = 'eventful_replace_layout';

    /**
     * Current-page context to inject into the layout query while replacing a
     * listing page in "no_change" / "change_sort" mode. Null when not replacing.
     *
     * @var array|null
     */
    private $context = null;

    /**
     * The active retrieval mode for the layout currently being rendered.
     *
     * @var string
     */
    private $replace_way = '';

    /**
     * The Tribe context for the view currently being rendered. The Events
     * Calendar exposes the active search keyword / taxonomy here, which WordPress
     * conditionals (is_search/is_tax) do NOT reflect for event tag and search
     * pages, so we read it to detect those page types reliably.
     *
     * @var object|null
     */
    private $tribe_context = null;  

    /**
     * Resolve the current request to a Replace Layout page key.
     *
     * Order matters: more specific contexts are checked first. The bootstrap
     * filter only fires inside an events view, so these conditionals already run
     * in event context.
     *
     * @since 2.3.0
     *
     * @return string Empty string when no event page matches.
     */
    private function current_page_key()
    {
        // Category: WordPress reports the taxonomy correctly here.
        if (is_tax('tribe_events_cat') || $this->context_has('event_category')) {
            return 'tax-tribe_events_cat';
        }

        // Event tag: The Events Calendar's event-tag view does NOT set
        // is_tax('post_tag'), so detect it via is_tag()/the queried term. Standard
        $queried = get_queried_object();
        $is_event_tag = is_tag()
            || ($queried instanceof \WP_Term && isset($queried->taxonomy) && 'post_tag' === $queried->taxonomy)
            || $this->context_has('post_tag');
        if ($is_event_tag) {
            return 'post_tag';
        }

        // Search: tribe-bar-search does not flip is_search(); the keyword lives in
        // the Tribe context.
        if (is_search() || $this->context_has('keyword')) {
            return 'tribe_events_search';
        }

        // Plain events archive (checked last: it is also true on the views above).
        if (is_post_type_archive('tribe_events')) {
            return 'post_type-tribe_events';
        }

        return '';
    }

    /**
     * Whether the Tribe context holds a non-empty value for the given key.
     *
     * @since 2.3.0
     *
     * @param string $key Tribe context key (keyword|post_tag|event_category).
     * @return bool
     */
    private function context_has($key)
    {
        if (! is_object($this->tribe_context) || ! method_exists($this->tribe_context, 'get')) {
            return false;
        }
        $value = $this->tribe_context->get($key, '');
        return ! empty($value);
    }

    /**
     * Strip filter UI settings from the layout's view options.
     *
     * Runs on `eventful_view_options` (priority 999) during shortcode rendering
     * for no_change and change_sort modes. The filter bar template checks
     * `$options['eventful_advanced_filter']` to decide what filter widgets to
     * render (search box, category buttons, tag buttons, date picker, etc.).
     * Clearing it hides all filter UI for the two modes that must not show any
     * filter controls.
     *
     * @since 2.3.1
     *
     * @param array $options The layout view options read from post meta.
     * @return array
     */
    public function filter_view_options($options)
    {
        // Both no_change and change_sort: no filter widgets should be rendered.
        // The order-by setting (change_sort) is a query-level flag, not a UI widget.
        $options['eventful_advanced_filter'] = array();

        return $options;
    }

    /**
     * Rebuild the layout query so it matches the active retrieval mode.
     *
     * This filter runs on `eventful_query_args` (priority 999) and is the
     * single place that enforces what each replace_way actually means:
     *
     *  no_change   — Strip every shortcode filter. Carry only structural args
     *                (post_type, posts_per_page, paged, …) and set a natural
     *                upcoming/ASC order. Then scope to the current page context.
     *
     *  change_sort — Same stripping, but preserve the shortcode's orderby / order
     *                / meta_key so only sorting changes from TEC's default.
     *
     * The previous implementation only *added* context on top of the fully-built
     * shortcode query, so advanced filters (categories, tags, venues, dates,
     * keywords, event-type) from the shortcode still polluted the query in both
     * no_change and change_sort modes. This rewrite fixes that by first reducing
     * the args to a clean baseline before applying context.
     *
     * @since 2.3.1
     *
     * @param array $args The layout's assembled WP/Tribe query args.
     * @return array
     */
    public function inject_context($args)
    {
        // Keys that are structural to every query — not shortcode filter settings.
        $structural_keys = array(
            'post_type', 'suppress_filters', 'ignore_sticky_posts',
            'posts_per_page', 'paged', 'post_status',
            'ends_after', 'hide_subsequent_recurrences',
        );

        if ('no_change' === $this->replace_way) {
            // Keep only structural args; discard all shortcode-specific filters.
            $clean = array();
            foreach ($structural_keys as $key) {
                if (isset($args[$key])) {
                    $clean[$key] = $args[$key];
                }
            }
            // Natural chronological order — mirrors The Events Calendar's default.
            $clean['orderby']  = 'meta_value';
            $clean['meta_key'] = '_EventStartDate';
            $clean['order']    = 'ASC';
            // Show upcoming events only (matches TEC archive default behaviour).
            $clean['meta_query'] = array(
                'relation' => 'AND',
                array(
                    'key'     => '_EventStartDate',
                    'value'   => current_time('Y-m-d H:i:s'),
                    'compare' => '>=',
                    'type'    => 'DATETIME',
                ),
            );
            $args = $clean;

        } elseif ('change_sort' === $this->replace_way) {
            // Keep structural args plus the shortcode's order settings only.
            $clean = array();
            $order_keys = array('orderby', 'order', 'meta_key');
            foreach (array_merge($structural_keys, $order_keys) as $key) {
                if (isset($args[$key])) {
                    $clean[$key] = $args[$key];
                }
            }
            // Show upcoming events only (all other shortcode filters are ignored).
            $clean['meta_query'] = array(
                'relation' => 'AND',
                array(
                    'key'     => '_EventStartDate',
                    'value'   => current_time('Y-m-d H:i:s'),
                    'compare' => '>=',
                    'type'    => 'DATETIME',
                ),
            );
            $args = $clean;
        }

        // Apply the current-page context (taxonomy term, search keyword).
        if (is_array($this->context)) {
            foreach ($this->context as $key => $value) {
                if ('tax_query' === $key) {
                    $existing          = isset($args['tax_query']) && is_array($args['tax_query']) ? $args['tax_query'] : array();
                    $args['tax_query'] = array_merge($existing, $value);
                } else {
                    $args[$key] = $value;
                }
            }
        }

        return $args;
    }
}

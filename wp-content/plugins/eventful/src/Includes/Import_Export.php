<?php

/**
 * Export Import class.
 *
 * @link       https://themeatelier.net/
 * @since      2.1.5
 *
 * @package    Eventful
 * @subpackage Eventful/includes
 */

namespace ThemeAtelier\Eventful\Includes;

// don't call the file directly.
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Import Export
 */
class Import_Export
{
	/**
	 * Export
	 *
	 * @param  mixed $shortcode_ids Export Shortcode ids.
	 * @return object
	 */
	public function export($shortcode_ids)
	{
		$export = array();
		if (! empty($shortcode_ids)) {

			$post_in    = 'all_shortcodes' === $shortcode_ids ? '' : $shortcode_ids;
			$args       = array(
				'post_type'        => 'eventful',
				'post_status'      => array('inherit', 'publish'),
				'orderby'          => 'modified',
				'suppress_filters' => 1, // wpml, ignore language filter.
				'posts_per_page'   => 600,
				'post__in'         => $post_in,
			);
			$shortcodes = get_posts($args);
			if (! empty($shortcodes)) {
				foreach ($shortcodes as $shortcode) {
					$shortcode_export = array(
						'title'       => $shortcode->post_title,
						'original_id' => $shortcode->ID,
						'meta'        => array(),
					);
					foreach (get_post_meta($shortcode->ID) as $metakey => $value) {
						$shortcode_export['meta'][$metakey] = $value[0];
					}
					$export['shortcode'][] = $shortcode_export;
					unset($shortcode_export);
				}
				$export['metadata'] = array(
					'version' => EVENTFUL_VERSION,
					'date'    => gmdate('Y/m/d'),
				);
			}
			return $export;
		}
	}

	/**
	 * Export eventful by ajax.
	 *
	 * @return void
	 */
	public function export_shortcodes()
	{
		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
		if (! wp_verify_nonce($nonce, 'eventful_options_nonce')) {
			die();
		}
		$shortcode_ids = '';
		if (isset($_POST['eventful_ids'])) {
			$shortcode_ids = is_array($_POST['eventful_ids']) ? wp_unslash(array_map('absint', $_POST['eventful_ids'])) : sanitize_text_field(wp_unslash($_POST['eventful_ids']));
		}		
		$export = $this->export($shortcode_ids);

		if (is_wp_error($export)) {
			wp_send_json_error(
				array(
					'message' => $export->get_error_message(),
				),
				400
			);
		}

		if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
			// @codingStandardsIgnoreLine
			echo wp_json_encode($export, JSON_PRETTY_PRINT);
			die;
		}

		wp_send_json($export, 200);
	}

	/**
	 * Import shortcode.
	 *
	 * @param  mixed $shortcodes Import sliders shortcode array.
	 *
	 * @throws \Exception If the import fails.
	 * @return object
	 */
	public function import($shortcodes)
	{
		$errors = array();
		foreach ($shortcodes as $index => $shortcode) {
			$errors[$index] = array();
			$new_shortcode_id = 0;
			try {
				$new_shortcode_id = wp_insert_post(
					array(
						'post_title'  => isset($shortcode['title']) ? $shortcode['title'] : '',
						'post_status' => 'publish',
						'post_type'   => 'eventful',
					),
					true
				);
				if (is_wp_error($new_shortcode_id)) {
					throw new \Exception($new_shortcode_id->get_error_message());
				}

				if (isset($shortcode['meta']) && is_array($shortcode['meta'])) {
					foreach ($shortcode['meta'] as $key => $value) {
						update_post_meta(
							$new_shortcode_id,
							$key,
							maybe_unserialize(str_replace('{#ID#}', $new_shortcode_id, $value))
						);
					}
				}
			} catch (\Exception $e) {
				array_push($errors[$index], $e->getMessage());

				// If there was a failure somewhere, clean up.
				wp_trash_post($new_shortcode_id);
			}

			// If no errors, remove the index.
			if (! count($errors[$index])) {
				unset($errors[$index]);
			}

			// External modules manipulate data here.
			do_action('eventful_imported', $new_shortcode_id);
		}

		$errors = reset($errors);
		return isset($errors[0]) ? new \WP_Error('import_eventful_error', $errors[0]) : '';
	}

	/**
	 * Import eventful by ajax.
	 *
	 * @return void
	 */
	public function import_shortcodes()
	{
		$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

		if (! wp_verify_nonce($nonce, 'eventful_options_nonce')) {
			wp_send_json_error(array('message' => esc_html__('Error: Invalid nonce verification.', 'eventful')), 401);
		}
		// Don't worry sanitize after JSON decode below.
		// phpcs:ignore
		$data         = isset($_POST['shortcode']) ? $_POST['shortcode'] : '';
		$data         = json_decode(wp_unslash($data), true);
		$import_value = apply_filters('eventful_allow_import_tags', false);
		$shortcodes   = $import_value ? $data['shortcode'] : wp_kses_post_deep($data['shortcode']);
		if (! $data) {
			wp_send_json_error(
				array(
					'message' => esc_html__('Nothing to import.', 'eventful'),
				),
				400
			);
		}

		$status = $this->import($shortcodes);

		if (is_wp_error($status)) {
			wp_send_json_error(
				array(
					'message' => $status->get_error_message(),
				),
				400
			);
		}

		wp_send_json_success($status, 200);
	}
}

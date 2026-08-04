<?php
/**
 * An implementation of the Cost filter that applies to specific contexts.
 *
 * @since   4.9.0
 *
 * @package Tribe\Events\Filterbar\Views\V2\Filters
 */

namespace Tribe\Events\Filterbar\Views\V2\Filters;

use Tribe__Context as Context;
use Tribe__Utils__Array as Arr;

/**
 * Class Cost
 *
 * @since   4.9.0
 *
 * @package Tribe\Events\Filterbar\Views\V2\Filters
 */
class Cost extends \Tribe__Events__Filterbar__Filters__Cost {
	use Context_Filter;

	/**
	 * Parses the value from the context and builds it the way the base filter.
	 *
	 * @since 4.9.0
	 * @since 5.6.6 Normalized against nested-array request shapes; malformed range tokens are dropped
	 *            instead of being passed through to `explode()`/`preg_match()` as-is.
	 *
	 * @param mixed $raw_value The raw value.
	 *
	 * @return array<mixed> The parsed cost range values (`min`/`max` arrays or the `other` token); empty when nothing valid was submitted.
	 */
	public function parse_value( $raw_value ) {
		$value = (array) $raw_value;

		// A pre-parsed { min, max } range from the context.
		if ( isset( $value['min'], $value['max'] ) && is_scalar( $value['min'] ) && is_scalar( $value['max'] ) ) {
			return [
				[
					'min' => $value['min'],
					'max' => $value['max'],
				],
			];
		}

		/*
		 * Otherwise expect a list of "min-max" range tokens or the special 'other' token;
		 * drop anything malformed so the clause builder can trust the shape.
		 */
		$parsed = [];
		foreach ( $value as $range_token ) {
			if ( 'other' === $range_token ) {
				$parsed[] = $range_token;
				continue;
			}

			if ( ! is_string( $range_token ) || ! preg_match( '/^\s*(\d+)\s*-\s*(\d+)\s*$/', $range_token, $matches ) ) {
				continue;
			}

			$parsed[] = [
				'min' => $matches[1],
				'max' => $matches[2],
			];
		}

		return $parsed;
	}

	/**
	 * Builds the value that should be set in the query argument for the Cost filter.
	 *
	 * @since 4.9.0
	 *
	 * @param string|array $value       The value, as received from the context.
	 * @param string       $context_key The key used to fetch the `$value` from the Context.
	 * @param Context      $context     The context instance.
	 *
	 * @return array<string> An array price ranges, e.g. `0-9`.
	 */
	public static function build_query_arg_value( $value, $context_key, Context $context ) {
		return Arr::list_to_array( $value, ',' );
	}

	/**
	 * Filter the Cost filter is_checked conditional when it is checkboxes.
	 *
	 * @since 5.0.0
	 *
	 * @param boolean                   $special_is_checked Whether a special is checked condition has been met, Unused.
	 * @param array<string,integer>|int $value              An array or integer of the current fields value.
	 * @param array<string,integer>     $current_value      An array of the selected value(s).
	 * @param string                    $type               The type of field the filter displays as.
	 *
	 * @return boolean                  Whether the cost condition is met for a given value.
	 */
	public static function filter_is_checked( $is_checked, $value, $current_values, $type ) {

		if ( 'checkbox' !== $type ) {
			return false;
		}

		if ( ! is_array( $current_values ) ) {
			return false;
		}

		$selected = [];

		foreach ( $current_values as $current_value ) {
			$selected[] = is_array( $current_value ) ? implode( '-', $current_value ) : $current_value;
		}

		if ( in_array( $value, $selected ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Currency format the range label.
	 *
	 * @since 5.0.0
	 *
	 * @param string $label The default range label, unused.
	 * @param int    $min   The minimum value for the range.
	 * @param int    $min   The maximum value for the range.
	 *
	 * @return string The formatted range label.
	 */
	public static function filter_range_label( $label, $min, $max ) {

		// Get currency symbol and order.
		$currency_symbol           = tribe_get_option( 'defaultCurrencySymbol', '$' );
		$reverse_currency_position = tribe_get_option( 'reverseCurrencyPosition', false );
		$currency_order            = '%1$s%2$s - %1$s%3$s';

		if ( $reverse_currency_position ) {
			$currency_order = '%2$s%1$s - %3$s%1$s';
		}

		// Create the label based on currency and placement settings.
		return sprintf( $currency_order, $currency_symbol, $min, $max );
	}
}

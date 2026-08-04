<?php
/**
 * Centralized encoding helper for Filter Bar special character handling.
 *
 * @since 5.6.1
 *
 * @package Tribe\Filter_Bar
 */

namespace TEC\Filter_Bar;

/**
 * Centralized encoding helper for Filter Bar special character handling.
 *
 * This class provides consistent encoding/decoding methods to handle special characters
 * like +, &, # in additional field values throughout the Filter Bar system.
 * It replaces inconsistent usage of parse_str(), urldecode(), and URLSearchParams
 * that was causing double-encoding and character loss of special characters in field values.
 *
 * @since 5.6.1
 */
class Encoding_Helper {

	/**
	 * Safely encode values for URL parameters.
	 *
	 * This method ensures special characters are properly encoded for use in URLs
	 * without conflicts with URL parameter parsing.
	 *
	 * @since 5.6.1
	 *
	 * @param string $value The value to encode.
	 *
	 * @return string The encoded value safe for URL parameters.
	 */
	public static function encode_for_url( $value ) {
		if ( ! is_string( $value ) ) {
			return $value;
		}

		// Use rawurlencode to avoid conflicts with parse_str() behavior.
		// rawurlencode handles spaces as %20 instead of +, avoiding parse_str() conflicts.
		$encoded = rawurlencode( $value );

		/**
		 * Double-encode special characters to prevent browser URL processing issues:
		 * - + characters: %2B -> %252B (prevents + to space conversion)
		 * - # characters: %23 -> %2523 (prevents fragment identifier issues)
		 * - & characters: %26 -> %2526 (prevents query parameter splitting)
		 */
		$encoded = str_replace( '%2B', '%252B', $encoded );
		$encoded = str_replace( '%23', '%2523', $encoded );
		$encoded = str_replace( '%26', '%2526', $encoded );

		/**
		 * Filter the encoded value before returning.
		 *
		 * This allows custom filtering logic to be added for specific encoding needs.
		 *
		 * @since 5.6.1
		 *
		 * @param string $encoded The encoded value.
		 * @param string $value   The original value before encoding.
		 */
		$encoded = apply_filters( 'tec_events_filterbar_encoding_helper_encode_for_url', $encoded, $value );

		return $encoded;
	}

	/**
	 * Safely decode values from URL parameters.
	 *
	 * This method properly decodes values that were encoded with encode_for_url().
	 * It handles both single and double-encoded values.
	 *
	 * @since 5.6.1
	 *
	 * @param string $value The value to decode.
	 *
	 * @return string The decoded value.
	 */
	public static function decode_from_url( $value ) {
		if ( ! is_string( $value ) ) {
			return $value;
		}

		// Handle double-encoded values by decoding until no more changes occur.
		$decoded  = $value;
		$previous = '';

		while ( $decoded !== $previous ) {
			$previous = $decoded;
			$decoded  = rawurldecode( $decoded );
		}

		/**
		 * Filter the decoded value before returning.
		 *
		 * This allows custom filtering logic to be added for specific decoding needs.
		 *
		 * @since 5.6.1
		 *
		 * @param string $decoded The decoded value.
		 * @param string $value   The original value before decoding.
		 */
		$decoded = apply_filters( 'tec_events_filterbar_encoding_helper_decode_from_url', $decoded, $value );

		return $decoded;
	}

	/**
	 * Safely encode values for form submission.
	 *
	 * This method ensures special characters are properly encoded for HTML forms
	 * without breaking form processing.
	 *
	 * @since 5.6.1
	 *
	 * @param string $value The value to encode.
	 *
	 * @return string The encoded value safe for form submission.
	 */
	public static function encode_for_form( $value ) {
		if ( ! is_string( $value ) ) {
			return $value;
		}

		// Use htmlspecialchars to prevent HTML injection while preserving special characters.
		$encoded = htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' );

		/**
		 * Filter the form-encoded value before returning.
		 *
		 * This allows custom filtering logic to be added for specific form encoding needs.
		 *
		 * @since 5.6.1
		 *
		 * @param string $encoded The encoded value.
		 * @param string $value   The original value before encoding.
		 */
		$encoded = apply_filters( 'tec_events_filterbar_encoding_helper_encode_for_form', $encoded, $value );

		return $encoded;
	}

	/**
	 * Safely decode values from form submission.
	 *
	 * This method properly decodes values that were encoded with encode_for_form().
	 *
	 * @since 5.6.1
	 *
	 * @param string $value The value to decode.
	 *
	 * @return string The decoded value.
	 */
	public static function decode_from_form( $value ) {
		if ( ! is_string( $value ) ) {
			return $value;
		}

		// Use htmlspecialchars_decode to match the encoding used in encode_for_form().
		$decoded = htmlspecialchars_decode( $value, ENT_QUOTES );

		/**
		 * Filter the form-decoded value before returning.
		 *
		 * This allows custom filtering logic to be added for specific form decoding needs.
		 *
		 * @since 5.6.1
		 *
		 * @param string $decoded The decoded value.
		 * @param string $value   The original value before decoding.
		 */
		$decoded = apply_filters( 'tec_events_filterbar_encoding_helper_decode_from_form', $decoded, $value );

		return $decoded;
	}

	/**
	 * Parse URL query string with proper special character handling.
	 *
	 * This method replaces parse_str() with a more robust parser that handles
	 * special characters correctly.
	 *
	 * @since 5.6.1
	 *
	 * @param string $query_string The query string to parse.
	 *
	 * @return array The parsed query parameters.
	 */
	public static function parse_query_string( $query_string ) {
		if ( empty( $query_string ) ) {
			return [];
		}

		// Remove leading ? if present.
		$query_string = ltrim( $query_string, '?' );

		$parsed = [];
		$pairs  = explode( '&', $query_string );

		foreach ( $pairs as $pair ) {
			if ( empty( $pair ) ) {
				continue;
			}

			$parts = explode( '=', $pair, 2 );
			$key   = $parts[0] ?? '';
			$value = $parts[1] ?? '';

			// Check if this looks like a double-encoded pair (contains %25).
			if ( strpos( $pair, '%25' ) !== false ) {
				// Decode the key, but gate '+'->space on the RAW value: a protected literal '+'
				// is still '%252B' here (so the percent-encoding gate skips it), while a raw '+'
				// is a form-encoded space.
				$key   = self::decode_from_url( $parts[0] ?? '' );
				$value = self::decode_from_url( self::maybe_plus_to_space( $key, $parts[1] ?? '' ) );
			} else {
				// Normal encoding, decode key and value separately.
				$key   = self::decode_from_url( $key );
				$value = self::maybe_plus_to_space( $key, $value );

				// Then decode URL-encoded parts (like %2B -> +, %20 -> space).
				$value = self::decode_from_url( $value );
			}

			if ( empty( $key ) ) {
				continue;
			}

			// Handle array notation like field[0], field[1].
			if ( preg_match( '/^(.+)\[(\d+)\]$/', $key, $matches ) ) {
				$base_key = $matches[1];
				$index    = (int) $matches[2];

				if ( ! isset( $parsed[ $base_key ] ) ) {
					$parsed[ $base_key ] = [];
				}

				$parsed[ $base_key ][ $index ] = $value;
			} else {
				$parsed[ $key ] = $value;
			}
		}

		// Sort array indices to maintain proper order.
		foreach ( $parsed as $key => $value ) {
			if ( is_array( $value ) ) {
				ksort( $parsed[ $key ] );
				$parsed[ $key ] = array_values( $parsed[ $key ] );
			}
		}

		return $parsed;
	}

	/**
	 * Build URL query string with proper special character handling.
	 *
	 * This method builds query strings that are compatible with our custom parser.
	 *
	 * @since 5.6.1
	 *
	 * @param array $params The parameters to encode.
	 *
	 * @return string The encoded query string.
	 */
	public static function build_query_string( array $params ) {
		if ( empty( $params ) ) {
			return '';
		}

		$pairs = [];

		foreach ( $params as $key => $value ) {
			$encoded_key = self::encode_for_url( $key );

			if ( is_array( $value ) ) {
				foreach ( $value as $index => $item ) {
					$pairs[] = $encoded_key . '%5B' . $index . '%5D=' . self::encode_for_url( $item );
				}
			} else {
				$pairs[] = $encoded_key . '=' . self::encode_for_url( $value );
			}
		}

		return implode( '&', $pairs );
	}

	/**
	 * Test if a value contains problematic special characters.
	 *
	 * This method helps identify values that might cause encoding issues.
	 *
	 * @since 5.6.1
	 *
	 * @param string $value The value to test.
	 *
	 * @return bool True if the value contains problematic characters.
	 */
	public static function has_problematic_characters( $value ) {
		if ( ! is_string( $value ) ) {
			return false;
		}

		// Check for characters that cause issues with parse_str() and URL encoding.
		$problematic_chars = [ '+', '&', '#' ];

		foreach ( $problematic_chars as $char ) {
			if ( strpos( $value, $char ) !== false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Converts '+' to a space for custom-field filter values that are not percent-encoded.
	 *
	 * A '+' is only a space for `application/x-www-form-urlencoded` data. We limit the
	 * conversion to custom-field keys, and skip it when the value already contains a real
	 * `%XX` sequence (so a literal '%' such as "50% off" does not block a legitimate '+').
	 *
	 * @since 5.6.6
	 *
	 * @param string $key   The already-decoded parameter key.
	 * @param string $value The parameter value to normalize.
	 *
	 * @return string The value with '+' converted to spaces when applicable.
	 */
	private static function maybe_plus_to_space( string $key, string $value ): string {
		if ( strpos( $key, 'tribe__ecp_custom_' ) === false ) {
			return $value;
		}

		if ( preg_match( '/%[0-9A-Fa-f]{2}/', $value ) ) {
			return $value;
		}

		return str_replace( '+', ' ', $value );
	}
}

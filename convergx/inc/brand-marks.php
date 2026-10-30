<?php
/**
 * Brand mark symbols on visitor-facing copy.
 *
 * ConvergX → ConvergX®
 * Xpand    → Xpand™
 *
 * Display-layer only: database content is left unmarked so editors can keep
 * typing the bare names. Re-running the helpers is idempotent.
 *
 * @package convergx
 */

defined( 'ABSPATH' ) || exit;

/**
 * Append registered / trademark symbols to brand names in plain text.
 *
 * Skips matches that already carry a mark, and skips names glued into paths
 * or identifiers (e.g. /xpand/, #xpand-prep).
 *
 * @param mixed $text Text to process.
 * @return mixed
 */
function convergx_brand_marks( $text ) {
	if ( ! is_string( $text ) || '' === $text ) {
		return $text;
	}

	if ( false === strpos( $text, 'ConvergX' ) && false === strpos( $text, 'Xpand' ) ) {
		return $text;
	}

	$text = preg_replace(
		'/(?<![\w\/.#-])ConvergX(?!(?:[®\x{00AE}]|&reg;|&#0*174;|&#x0*ae;))/u',
		'ConvergX®',
		$text
	);

	$text = preg_replace(
		'/(?<![\w\/.#-])Xpand(?!(?:[™\x{2122}]|&trade;|&#0*8482;|&#x0*2122;))/u',
		'Xpand™',
		$text
	);

	return $text;
}

/**
 * Same as convergx_brand_marks(), but only touches text outside HTML tags.
 *
 * @param mixed $html HTML to process.
 * @return mixed
 */
function convergx_brand_marks_html( $html ) {
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}

	if ( false === strpos( $html, 'ConvergX' ) && false === strpos( $html, 'Xpand' ) ) {
		return $html;
	}

	return preg_replace_callback(
		'/(<!--.*?-->|<(script|style|code|pre|textarea)\b[^>]*>.*?<\/\2\s*>|<[^>]+>)|([^<]+)/is',
		static function ( $m ) {
			if ( isset( $m[1] ) && '' !== $m[1] ) {
				return $m[1];
			}

			return convergx_brand_marks( isset( $m[3] ) ? $m[3] : '' );
		},
		$html
	);
}

/**
 * Whether a field selector is structural (URL, mode, class) rather than prose.
 *
 * @param string $selector Field name.
 * @return bool
 */
function convergx_brand_marks_skip_selector( $selector ) {
	$skip = array(
		'surface',
		'hero_mode',
		'hero_class',
		'form_id',
		'url',
		'href',
		'cta_url',
		'hero_cta_url',
		'image',
		'logo',
		'product_ids',
	);

	$selector = strtolower( (string) $selector );

	if ( in_array( $selector, $skip, true ) ) {
		return true;
	}

	return (bool) preg_match( '/(_url|_href|_id|_ids|_class|_slug|_mode)$/', $selector );
}

add_filter( 'the_content', 'convergx_brand_marks_html', 20 );
add_filter( 'the_excerpt', 'convergx_brand_marks_html', 20 );
add_filter( 'widget_text', 'convergx_brand_marks_html', 20 );
add_filter( 'the_title', 'convergx_brand_marks', 20 );
add_filter( 'woocommerce_product_get_name', 'convergx_brand_marks', 20 );
add_filter( 'woocommerce_product_variation_get_name', 'convergx_brand_marks', 20 );

add_filter( 'document_title_parts', 'convergx_brand_marks_document_title', 20 );
/**
 * @param array $parts Title parts.
 * @return array
 */
function convergx_brand_marks_document_title( $parts ) {
	foreach ( $parts as $key => $value ) {
		if ( is_string( $value ) ) {
			$parts[ $key ] = convergx_brand_marks( $value );
		}
	}

	return $parts;
}

add_filter( 'gettext', 'convergx_brand_marks_gettext', 20, 3 );
/**
 * @param string $translation Translated text.
 * @param string $text        Original text.
 * @param string $domain      Text domain.
 * @return string
 */
function convergx_brand_marks_gettext( $translation, $text, $domain ) {
	if ( 'convergx' !== $domain ) {
		return $translation;
	}

	return convergx_brand_marks( $translation );
}

add_filter( 'ngettext', 'convergx_brand_marks_ngettext', 20, 5 );
/**
 * @param string $translation Translated text.
 * @param string $single      Singular.
 * @param string $plural      Plural.
 * @param int    $number      Count.
 * @param string $domain      Text domain.
 * @return string
 */
function convergx_brand_marks_ngettext( $translation, $single, $plural, $number, $domain ) {
	if ( 'convergx' !== $domain ) {
		return $translation;
	}

	return convergx_brand_marks( $translation );
}

add_filter( 'convergx_field', 'convergx_brand_marks_field', 20, 3 );
/**
 * @param mixed  $value    Field value.
 * @param string $selector Field name.
 * @param int    $post_id  Post ID.
 * @return mixed
 */
function convergx_brand_marks_field( $value, $selector, $post_id ) {
	unset( $post_id );

	if ( ! is_string( $value ) || convergx_brand_marks_skip_selector( $selector ) ) {
		return $value;
	}

	return convergx_brand_marks( $value );
}

add_filter( 'acf/format_value/type=text', 'convergx_brand_marks_acf_text', 20, 3 );
add_filter( 'acf/format_value/type=textarea', 'convergx_brand_marks_acf_text', 20, 3 );
add_filter( 'acf/format_value/type=wysiwyg', 'convergx_brand_marks_acf_html', 20, 3 );
/**
 * @param mixed $value   Field value.
 * @param int   $post_id Post ID.
 * @param array $field   Field settings.
 * @return mixed
 */
function convergx_brand_marks_acf_text( $value, $post_id, $field ) {
	unset( $post_id );

	if ( ! is_string( $value ) ) {
		return $value;
	}

	$name = isset( $field['name'] ) ? $field['name'] : '';
	if ( convergx_brand_marks_skip_selector( $name ) ) {
		return $value;
	}

	return convergx_brand_marks( $value );
}

/**
 * @param mixed $value   Field value.
 * @param int   $post_id Post ID.
 * @param array $field   Field settings.
 * @return mixed
 */
function convergx_brand_marks_acf_html( $value, $post_id, $field ) {
	unset( $post_id, $field );

	return convergx_brand_marks_html( $value );
}

<?php
namespace NV\Plugins\MetaBox;

/**
 * Aids in dynamic generation of HTML.
 *
 * @since Nouveau 1.0
 */
class Html {

	/**
	 * Turns an associative array into HTML-ready attribute-value pairs.
	 *
	 * Any array values which are also arrays are turned into space-delimited
	 * word values (in the vein of the CSS classes).
	 *
	 * @param array $atts An associative array of attributes and values.
	 *
	 * @return string Attribute-value pairs ready to be used in an HTML element.
	 */
	public static function atts( $atts ) {
		$return = '';

		foreach ( $atts as $att => $val ) {

			$return .= sprintf( ' %s="%s"',
				sanitize_title_with_dashes( $att ),
				( is_array( $val ) || is_object( $val ) ) ? esc_attr( implode( $val, ' ' ) ) : esc_attr( $val ) );
		}

		return $return;
	}

	/**
	 * Used internally to merge default values with the $atts passed to an HTML
	 * shortcut method.
	 *
	 * @param array $defaults An associative array of default key=>value pairs
	 * @param array $atts     Optional. Override specified attributes.
	 *
	 * @return array Returns an array where the defaults are overwritten with the new values
	 */
	protected static function atts_default( $defaults = array(), $atts = array() ) {
		return $atts = array_merge( $defaults, $atts );
	}


	/**
	 * Generates HTML for any tag.
	 *
	 * @param string  $tag      The element name.
	 * @param array   $atts     Optional. Associative array (recommended) or a string containing pre-processed attributes.
	 * @param string  $content  Optional. Any content (html) to used inside the element.
	 * @param boolean $singletag Optional. Whether this tag is self-contained (such as <img/> or <br/>)
	 * @param boolean $selfclose Optional. If $singletag is true, whether the element should be automatically closed.
	 *
	 * @return string Returns the assembled element html.
	 */
	public static function gen( $tag, $atts = array(), $content = '', $singletag = false, $selfclose = true ) {
		// Generate the element's opening tag (sans closing bracket)
		$return = sprintf( '<%s%s', $tag, ( is_array( $atts ) ) ? self::atts( $atts ) : $atts );

		// Determine how the element closes...
		if ( ! empty( $content ) || !$singletag ) {
			// Content with an explicit closing tag
			$return .= sprintf( '>%s</%s>', $content, $tag );
		} else {
			// Self-closed
			$return .= ( $selfclose )?'/':'';
			$return .= '>';
		}
		
		$return .= "\r\n\r\n";

		return $return;
	}


	/**
	 * Returns a randomized element id.
	 *
	 * @since Nouveau 1.0
	 * @return string A 9-digit randomized attribute-safe id. e.g. "rand-1459"
	 */
	public static function randomId() {
		return 'rand-' . mt_rand( 1000, 9999 );
	}


	/**
	 * Takes an opening HTML tag and some content, then automagically generates the closing tag after any provided
	 * content. Use sparingly!
	 *
	 *
	 * @since Nouveau 1.0
	 *
	 * @param string $openingTag
	 * @param string $content
	 *
	 * @return string The full HTML string with closing tag.
	 */
	public static function wrap( $openingTag, $content = '' ) {
		$match = array();

		preg_match( '/^\<([a-zA-Z]+)/', $openingTag, $match );

		if ( empty( $match[1] ) ) {
			trigger_error( sprintf(__( 'Invalid html element was passed to %s:wrap', 'nvLangScope' ),__CLASS__), E_USER_WARNING );
		}

		$openingTag .= sprintf( '%s</%s>', $content, $match[1] );

		return $openingTag;
	}

}
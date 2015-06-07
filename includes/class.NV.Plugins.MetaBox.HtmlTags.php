<?php
namespace NV\Plugins\MetaBox;

/**
 * Includes shortcuts to speed up the easy generation of dynamic HTML using the
 * HtmlGen helper class.
 *
 * @since Nouveau 1.0
 */
class HtmlTags extends Html {

	/**
	 * Returns an <a> tag.
	 *
	 * @param        $atts An associative array of attributes (such as href)
	 * @param string $content
	 * @param bool   $echo
	 *
	 * @return string
	 */
	public static function a( $href = '#', $atts = array(), $content = '', $echo = false ) {
		$atts = parent::atts_default(
			array(
				'href' => $href,
			),
			$atts
		);

		$return = parent::gen( 'a', $atts, $content );

		if ( $echo ) {
			echo $return;
		}

		return $return;
	}

	/**
	 * Returns a <div> tag.
	 *
	 * @param array  $atts
	 * @param string $content
	 * @param bool   $echo
	 *
	 * @return string
	 */
	public static function div( $atts = array(), $content = '', $echo = false ) {
		$return = parent::gen( 'div', $atts, $content );

		if ( $echo ) {
			echo $return;
		}

		return $return;
	}

	/**
	 * Returns a valid <img> tag.
	 *
	 * @param string $src
	 * @param array  $atts
	 * @param bool   $echo
	 *
	 * @return string
	 */
	public static function img( $src = '', $atts = array(), $echo = false ) {
		$atts = parent::atts_default(
			array(
				'src' => ( ! empty( $src ) ) ? $src : '',
				'alt' => '',
			),
			$atts
		);

		$return = parent::gen( 'img', $atts, '', true );

		if ( $echo ) {
			echo $return;
		}

		return $return;
	}

	/**
	 * Returns a form element.
	 *
	 * Notice: This is a wrapper method. Generate your other HTML first and pass it in as $content.
	 *
	 * @param string $method
	 * @param string $action
	 * @param string $content
	 * @param array  $atts
	 * @param bool   $echo
	 *
	 * @return string
	 */
	public static function form( $method = 'GET', $action = '', $content = '', $atts = array(), $echo = false ) {
		$atts = parent::atts_default(
			array(
				'id'   => ( ! empty( $name ) ) ? $name : parent::randomId(),
				'method' => $method,
				'action' => $action,
			),
			$atts
		);

		$return = parent::gen('form',$atts,$content);

		if ( $echo ) {
			echo $return;
		}

		return $return;
	}

	/**
	 * Returns an <input> tag of type "text".
	 *
	 * @param string $name
	 * @param array  $atts
	 * @param bool   $echo
	 *
	 * @return string
	 */
	public static function input( $name, $atts = array(), $echo = false ) {
		
		$atts = parent::atts_default(
			array(
				'id'   => ( ! empty( $name ) ) ? $name : parent::randomId(),
				'name' => $name,
				'type' => 'text',
				'value'=> '',
			),
			$atts
		);

		$return = parent::gen( 'input', $atts, '', true );

		if ( $echo ) {
			echo $return;
		}

		return $return;
	}

	/**
	 * Alias for input() method.
	 *
	 * @param string $name
	 * @param array  $atts
	 * @param bool   $echo
	 *
	 * @return string
	 */
	public static function inputText( $name = '', $value = '', $atts = array(), $echo = false ) {
		$atts = parent::atts_default(
			array(
				'value' => $value,
				'placeholder' => '',
			),
			$atts
		);
		return self::input( $name, $atts, $echo );
	}

	/**
	 * Returns a checkbox input element.
	 *
	 * @param string $name
	 * @param array  $atts
	 * @param bool   $echo
	 *
	 * @return string
	 */
	public static function inputCheckbox( $name = '', $checked = false, $atts = array(), $echo = false ) {
		$atts['type'] = 'checkbox';
		
		if ($checked) {
			$atts['checked'] = 'checked';
		}
		
		return self::input( $name, $atts, $echo);
	}

	/**
	 * Returns a radio button input element.
	 *
	 * @param string $name
	 * @param array  $atts
	 * @param bool   $echo
	 *
	 * @return string
	 */
	public static function inputRadio( $name = '', $checked = false, $atts = array(), $echo = false ) {
		$atts['type'] = 'radio';

		if ($checked) {
			$atts['checked'] = 'checked';
		}
		
		return self::input( $name, $atts, $echo);
	}
	
	/**
	 * Create a hidden form field.
	 *
	 * @param string $name
	 * @parem string $value
	 * @param array  $atts
	 * @param bool   $echo
	 *
	 * @return string
	 */
	public static function inputHidden( $name = '', $value='', $atts = array(), $echo = false ) {
		$atts['type'] = 'hidden';
		$atts['value'] = $value;
		
		return self::input( $name, $atts, $echo);
	}

	/**
	 * Returns a <textarea> element.
	 *
	 * @param string $name
	 * @param array  $atts
	 * @param bool   $echo
	 *
	 * @return string
	 */
	public static function textarea( $name = '', $text = '', $atts = array(), $echo = false ) {
		$atts = parent::atts_default(
			array(
				'id'   => ( ! empty( $name ) ) ? $name : parent::randomId(),
				'name' => ( ! empty( $name ) ) ? $name : 'NOT_SET',
			),
			$atts
		);

		$return = parent::gen( 'textarea', $atts, $text );

		if ( $echo ) {
			echo $return;
		}

		return $return;
	}

	/**
	 * Generates a <select> dropdown element.
	 *
	 * Content can be passed as an associative array, but a plain string will be accepted as well. If an array is used,
	 * it should be formatted 'value' => 'Visible Text' and if $current is set, this method will find and select the
	 * respective element.
	 *
	 * @param string $name      The value to use for HTML name and id attributes
	 * @param array  $content   An array used to populate the select element with options
	 * @param mixed  $current   The current/default selected option (if $content is array)
	 * @param array  $atts
	 * @param bool   $echo
	 *
	 * @return string
	 */
	public static function select( $name = '', $content = false, $current = null, $atts = array(), $echo = false ){

		$atts = parent::atts_default(
			array(
				'id'   => ( ! empty( $name ) ) ? $name : parent::randomId(),
				'name' => ( ! empty( $name ) ) ? $name : 'NOT_SET',
			),
			$atts
		);

		$options = '';

		if ( is_array($content) ) {
			foreach ( $content as $optVal => $optText ) {
				$options .= self::option( $optText, $optVal, ($optVal == $current) );
			}
			$content = $options;
		}

		$return = parent::gen( 'select', $atts, $content );

		if ( $echo ) {
			echo $return;
		}

		return $return;
	}

	/**
	 * Generates an <option> element for use in a select dropdown.
	 *
	 * @param string $text      Visible text for the option
	 * @param string $value     Value to use for the option
	 * @param bool   $selected  Should this option be selected?
	 * @param array  $atts
	 * @param bool   $echo
	 *
	 * @return string
	 */
	public static function option( $text = '', $value = '', $selected = false, $atts = array(), $echo = false ){
		$atts = parent::atts_default(
			array(
				'value' => $value,
			),
			$atts
		);

		if ( $selected ) {
			$atts['selected'] = 'selected';
		}

		$return = parent::gen( 'option', $atts, $text );

		if ( $echo ) {
			echo $return;
		}

		return $return;
	}

	/**
	 * Returns a label element.
	 *
	 * @param string $text The text or content for the label.
	 * @param array  $atts
	 * @param bool   $echo
	 *
	 * @return string
	 */
	public static function label( $text='', $atts = array(), $echo = false ) {

		$return = parent::gen( 'label', $atts, $text );

		if ( $echo ) {
			echo $return;
		}

		return $return;
	}

}
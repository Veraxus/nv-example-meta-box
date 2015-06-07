<?php

namespace NV\Plugins;

/**
 * Class MetaBoxBuilder
 *
 * Used to automatically generate complete WordPress admin widgets, including HTML
 * and automatic population, validation, and saving.
 *
 * You can utilize this in just two steps…
 *
 * Step 1: Register your settings using /NV/Plugins/MetaBox/MetaBoxBuilder::register_setting()
 * Step 2: Register your metabox using /NV/Plugins/MetaBox/MetaBoxBuilder::build_metabox() as the callback.
 *
 * Everything else is handled for you! Alternatively, you can define your own template file and fetch the
 * form field using get_field()
 *
 * @package NV\Plugins\Widgets
 */
class MetaBox {

	public static function init() {
		global $NOUVEAU;

		// Register a global to manage metabox registrations
		$NOUVEAU['MetaBoxes'] = array();

		// Use standard hooks
		add_action( 'save_post', array( __CLASS__, 'save_metaboxes' ) );

	}


	/**
	 * @param       $meta_key
	 * @param       $box_id
	 * @param array $args
	 */
	public static function register_setting( $meta_key, $box_id, $args = array() ) {
		global $NOUVEAU;

		$args                                         = array_merge( array(
			'box_id'      => $box_id,
			'meta_key'    => $meta_key,
			// Visible label for this field
			'label'       => '',
			// Type of field this should be: text, textarea, select, radio, checkbox, hidden
			'type'        => 'text',
			// Placeholder text, if appropriate
			'placeholder' => '',
			// Default value or selection for this setting, if any.
			'value'       => '',
			// Assoc array of options for select, radio, and checkbox lists ('value' => 'visible text')
			'list'        => false,
			// Help text to display below the field
			'howto'       => false,
			// Whether this should be automatically saved
			'save'        => true,
			// TODO: A validation expression (regex), if desired
			'validate'    => false,
			// TODO:. String. Meta key to use if you want to group this setting with others into a single serialized setting list.
			'serialize'   => false,
		), $args );
		$NOUVEAU['MetaBoxes'][ $box_id ][ $meta_key ] = $args;
	}

	/**
	 * @param string  $box_id
	 * @param string  $meta_key
	 * @param string  $post
	 * @param boolean $label
	 *
	 * @return bool
	 */
	public static function get_field( $box_id, $meta_key, $post = null, $label = true ) {
		global $NOUVEAU;

		// Ensure the meta key is set
		if ( ! isset( $NOUVEAU['MetaBoxes'][ $box_id ][ $meta_key ] ) ) {
			trigger_error( __( 'Specified NOUVEAU meta key is not registered', 'nvLangScope' ) );

			return false;
		}

		// If post_id isn't set, try to get it
		if ( is_object( $post ) ) {
			$post = $post->ID;
		}
		if ( empty( $post ) ) {
			$post = get_post();
			$post = $post->ID;
		}

		// Fetch the field and data (if applicable)
		$field = $NOUVEAU['MetaBoxes'][ $box_id ][ $meta_key ];
		$data  = get_post_meta( $post, $meta_key, true ); //!is_array($field['list'])
		$item  = '';

		if ( $label ) {
			$item .= MetaBox\HtmlTags::label( '<strong>' . $field['label'] . '</strong>', array(
				'for' => $field['meta_key']
			) );
		}

		// Generate the appropriate field
		switch ( $NOUVEAU['MetaBoxes'][ $box_id ][ $meta_key ]['type'] ) {

			// ---------------------------------------------------------------------------
			// == HIDDEN =================================================================
			case 'hidden':
				$item .= MetaBox\HtmlTags::inputHidden( $field['meta_key'], ( $data ) ? $data : $field['value'] );
				break;
			
			// ---------------------------------------------------------------------------
			// == TEXTAREA ===============================================================
			case 'textarea':
				$item .= MetaBox\HtmlTags::textarea( $field['meta_key'], ( $data ) ? $data : $field['value'], array(
					'placeholder' => $field['placeholder']
				) );
				break;

			// ---------------------------------------------------------------------------
			// == SELECT =================================================================
			case 'select':
				$item .= MetaBox\HtmlTags::select( $field['meta_key'], $field['list'], $data );
				break;

			// ---------------------------------------------------------------------------
			// == RADIO BUTTONS & LISTS ==================================================
			case 'radio':
				if ( $field['list'] ) {
					$loops = 1;
					foreach ( $field['list'] as $lvalue => $ltext ) {
						$checked = is_array( $data ) && in_array( $lvalue, $data );

						if ( ! $checked && $field['value'] == $lvalue ) {
							$checked = true;
						}

						$item .= MetaBox\HtmlTags::label(
							MetaBox\HtmlTags::inputRadio( $field['meta_key'] . '[]', $checked, array(
								'value' => $lvalue,
								'id'    => $field['meta_key'] . $loops,
							) ) . $ltext, array() );
						$loops ++;
					}
				} else {
					$item .= MetaBox\HtmlTags::label( $field['label'], array(),
						MetaBox\HtmlTags::inputRadio( $field['meta_key'] ) );
				}
				break;

			// ---------------------------------------------------------------------------
			// == CHECKBOX & CHECKBOX LIST ===============================================
			case 'checkbox':
				if ( $field['list'] ) {
					$loops = 1;
					foreach ( $field['list'] as $lvalue => $ltext ) {
						$checked = is_array( $data ) && in_array( $lvalue, $data );

						if ( ! $checked && $field['value'] ) {
							$checked = true;
						}

						$item .= MetaBox\HtmlTags::label( MetaBox\HtmlTags::inputCheckbox( $field['meta_key'] . '[]', ( is_array( $data ) && in_array( $lvalue, $data ) ), array(
								'value' => $lvalue,
								'id'    => $field['meta_key'] . $loops,
							) ) . ' ' . $ltext, array() );
						$loops ++;
					}
				} else {
					$item .= MetaBox\HtmlTags::label( MetaBox\HtmlTags::inputCheckbox( $field['meta_key'] ) . ' ' . $field['label'], array() );
				}
				break;

			// ---------------------------------------------------------------------------
			// == TEXTBOX ================================================================
			case 'text':
			case 'input':
			default:
				$item .= MetaBox\HtmlTags::input( $field['meta_key'], array(
					'placeholder' => $field['placeholder'],
					'value'       => ( $data ) ? $data : $field['value']
				) );
				break;
		}

		return $item;

	}


	/**
	 * Dynamically build the metabox when this function is used as the callback.
	 *
	 * @param $post
	 * @param $args
	 */
	public static function build_metabox( $post, $args ) {
		global $NOUVEAU;

		$output = '';

		if ( isset( $NOUVEAU['MetaBoxes'][ $args['id'] ] ) ) {

			// Loop through each registered setting for this meta box…
			foreach ( $NOUVEAU['MetaBoxes'][ $args['id'] ] as $field ) {
				$item = sprintf( '<p>%s</p>', self::get_field( $args['id'], $field['meta_key'], $post ) );

				if ( $field['howto'] ) {
					$item .= sprintf( '<p class="howto">%s</p>', $field['howto'] );
				}

				$output .= $item;
			}

			// One nonce for the whole box
			ob_start();
			wp_nonce_field( $args['id'], $args['id'] . '_nonce' );
			$output .= ob_get_clean();
		};
		
		// Wrap the whole thing in a magic metabox element so we can isolate it with CSS
		$output = sprintf('<div class="magic-metabox">%s</div>', $output);

		echo $output;

	}


	/**
	 * Automagically save every registered setting/field
	 */
	public static function save_metaboxes( $post_id ) {
		global $NOUVEAU;

		// Fetch all boxes
		foreach ( $NOUVEAU['MetaBoxes'] as $mb_key => $mb_fields ) {

			if ( self:: verify_save( $mb_key . '_nonce', $mb_key, $post_id ) ) {
				// Fetch all fields in current box
				foreach ( $mb_fields as $field ) {
					
					// If save isn't true, skip
					if ( !$field['save'] ) {
						continue;
					}
					
					if ( isset( $_POST[ $field['meta_key'] ] ) ) {
						$new_value = $_POST[ $field['meta_key'] ];
					} 
					else {
						delete_post_meta( $post_id, $field['meta_key'] );
						continue;
					}

					switch ( $field['type'] ) {
						case 'checkbox':
						case 'radio':
							if ( is_array( $new_value ) && $field['list'] ) {
								// Arrays are automatically serialized, so no sanitizing
								break;
							}
						case 'select':
						case 'input':
						case 'text':
						case 'textarea':
						default:
							$new_value = sanitize_text_field( $new_value );
							break;
					}

					// save
					update_post_meta( $post_id, $field['meta_key'], $new_value );

				}
			}

		}

	}


	/**
	 * Performs meta box save validation
	 *
	 * @param $nonce_name
	 * @param $none_action
	 * @param $post_id
	 *
	 * @return bool
	 */
	public static function verify_save( $nonce_name, $none_action, $post_id ) {

		// VALIDATE NONCE & AUTOSAVE
		if ( ! isset( $_POST[ $nonce_name ] ) ) {
			return false;
		}
		if ( ! wp_verify_nonce( $_POST[ $nonce_name ], $none_action ) ) {
			return false;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		// VALIDATE PERMISSIONS
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return false;
		}

		// EVERYTHING CHECKS OUT
		return true;
	}


}

MetaBox::init();
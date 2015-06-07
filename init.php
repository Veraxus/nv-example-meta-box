<?php
/*
Plugin Name: NOUVEAU Meta Boxes Example
Plugin URI: http://nouveauframework.com/downloads/plugins/
Description: A simple, functional WordPress plugin that serves as an example for creating new meta boxes for use on admin editor screens.
Author: Matt van Andel
Version: 1.0
Author URI: http://mattstoolbox.com/
License: GPLv2 or later
*/

namespace NV\Plugins;

NV_Example_Meta_Boxes::init();

/**
 * Example class for creating custom meta boxes in WordPress. Uses a little magic, but can also be overridden for
 * folks who want a little more control.
 * 
 * You can add custom meta boxes in just two steps…
 * 
 * 1. Register your new meta boxes using add_meta_box() within the register_metaboxes() method below.
 * 2. Register any settings/form elements you want using MetaBox::register_setting() within the register_settings() method below.
 * 3. Customize your CSS and Javascript, if desired, in the assets folder.
 */
class NV_Example_Meta_Boxes {

	/**
	 * STEP 1: REGISTER YOUR METABOXES HERE…
	 * 
	 * Register any new meta boxes here. Use the magic callback array( '\NV\Plugins\MetaBox', 'build_metabox' ) to
	 * automatically create a new 
	 * 
	 * Note: These examples fetch all post types and add meta boxes to all of them. Customize to your own needs.
	 *
	 * http://codex.wordpress.org/Function_Reference/add_meta_box
	 */
	public static function register_metaboxes() {
		//Get all registered post types...
		$screens = get_post_types();

		// Loop through the post types and add meta box to each
		foreach ( $screens as $screen ) {

			// EXAMPLE 1: Add a meta box using the magic form builder function
			add_meta_box(
				'magic-meta-box',   // HTML slug for box id
				__( 'Magic Custom Meta Box', 'nvLangScope' ), // Visible title
				array( '\NV\Plugins\MetaBox', 'build_metabox' ), // Magic callback. Used to render the meta box HTML
				$screen,            // The slug of the post type you want to add this meta box to.
				'side',             // Context. Where on the screen should this show up? Options: 'normal', 'advanced', or 'side'
				'high'              // Priority. Options: 'high', 'core', 'default' or 'low'
			);

			// EXAMPLE 2: Add a meta box using a handcrafted metabox
			add_meta_box(
				'custom-meta-box',  // HTML slug for box id
				__( 'Custom Meta Box', 'nvLangScope' ), // Visible title
				function ( $post, $args ) { // Anonymous function to include metabox template
					include 'templates/custom_metabox.php';
				},
				$screen,            // The slug of the post type you want to add this meta box to.
				'side',             // Context. Where on the screen should this show up? Options: 'normal', 'advanced', or 'side'
				'high'              // Priority. Options: 'high', 'core', 'default' or 'low'
			);

		}
	}


	/**
	 * STEP 2: REGISTER YOUR SETTINGS…
	 * 
	 * Register your settings here with MetaBox::register_setting(). Once registered, saving is handled for you and you
	 * have access to functions that can automatically generate metaboxes and/or fields.
	 * 
	 * Examples are provided below… delete them and create your own.
	 * 
	 * Note: The order of registration controls the order of display.
	 */
	public static function register_settings() {

		// AUTOMAGIC META BOX
		MetaBox::register_setting( '_nv_example_meta_field', 'magic-meta-box', array(
			'label'       => __( 'Example Field:', 'nvLangScope' ),
			'placeholder' => __( 'Enter a value…', 'nvLangScope' ),
		) );
		MetaBox::register_setting( '_nv_example_meta_checkboxes', 'magic-meta-box', array(
			'type'  => 'checkbox',
			'label' => __( 'Example Checklist:', 'nvLangScope' ),
			'list'  => array(
				'cb1' => __( 'Item 1', 'nvLangScope' ),
				'cb2' => __( 'Item 2', 'nvLangScope' ),
				'cb3' => __( 'Item 3', 'nvLangScope' ),
			),
		) );
		MetaBox::register_setting( '_nv_example_meta_radio', 'magic-meta-box', array(
			'type'  => 'radio',
			'label' => __( 'Example Radio List:', 'nvLangScope' ),
			'list'  => array(
				'radio1' => __( 'Item 1', 'nvLangScope' ),
				'radio2' => __( 'Item 2', 'nvLangScope' ),
				'radio3' => __( 'Item 3', 'nvLangScope' ),
			),
			'value' => 'radio1', // which list item(s) to select by default
		) );
		MetaBox::register_setting( '_nv_example_meta_textarea', 'magic-meta-box', array(
			'type'  => 'textarea',
			'label' => __( 'Example Textarea:', 'nvLangScope' ),
			'placeholder' => __('Your text goes here!','nvLangScope'),
			'howto' => __( 'You can add a paragraph of help text below any setting.', 'nvLangScope' ),
		) );

		// CUSTOM (HAND-CRAFTED) META BOX
		MetaBox::register_setting( '_nv_example_meta_field2', 'custom-meta-box', array(
			'label'       => __( 'Example Field:', 'nvLangScope' ),
			'placeholder' => __( 'Enter a value…', 'nvLangScope' ),
		) );
		MetaBox::register_setting( '_nv_example_meta_dropdown', 'custom-meta-box', array(
			'type'  => 'select',
			'label' => __( 'Example Dropdown:', 'nvLangScope' ),
			'list'  => array(
				''  => __( '-- Select One --', 'nvLangScope' ),
				'1' => __( 'Option 1', 'nvLangScope' ),
				'2' => __( 'Option 2', 'nvLangScope' ),
			),
		) );

	}

	/**
	 * Constructor. Generally, you don't need to mess with this.
	 */
	public static function init() {

		require 'includes/class.NV.Plugins.MetaBox.php';
		require 'includes/class.NV.Plugins.MetaBox.Html.php';
		require 'includes/class.NV.Plugins.MetaBox.HtmlTags.php';

		// Register metabox settings / fields
		self::register_settings();

		// Add the meta boxes
		add_action( 'add_meta_boxes', array( __CLASS__, 'register_metaboxes' ) );

		// Enqueue optionals styles & js
		add_action( 'admin_enqueue_scripts', function ( $hook ) {
			wp_enqueue_script( 'nv_meta_scripts', plugin_dir_url( __FILE__ ) . 'assets/js/scripts.js' );
			wp_enqueue_style( 'nv_meta_styles', plugin_dir_url( __FILE__ ) . 'assets/css/styles.css' );
		} );
	}


}
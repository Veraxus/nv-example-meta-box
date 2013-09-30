<?php
/*
Plugin Name: NOUVEAU Meta Boxes Example
Plugin URI: http://nouveauframework.com/downloads/plugins/
Description: A simple, functional WordPress plugin that serves as an example for creating new meta boxes for use on admin editor screens.
Author: Matt Van Andel
Version: 1.0
Author URI: http://mattstoolbox.com/
License: GPLv2 or later
*/

NV_Example_Meta_Boxes::init();

/**
 * Our Example Widget class
 */
class NV_Example_Meta_Boxes extends WP_Widget
{

    public static function init()
    {
        // Add the meta boxes
        add_action( 'add_meta_boxes', array('NV_Example_Meta_Boxes','register') );

        // Process meta boxes on post save
        add_action( 'save_post', array('NV_Example_Meta_Boxes','metabox_save') );
    }

    /**
     * Used by the add_meta_boxes hook to register new meta boxes
     *
     * http://codex.wordpress.org/Function_Reference/add_meta_box
     */
    public static function register()
    {
        //Get all registered post types...
        $screens = get_post_types();

        //OR Specify specific post types...
        //$screens = array( 'post', 'page' );

        // Loop through the post types and add meta box to each
        foreach ( $screens as $screen ) {

            add_meta_box(
                'meta-box-id',   //HTML id for box
                __( 'Meta Box Title', 'nvLangScope' ), // Visible title
                array('NV_Example_Meta_Boxes','metabox'), //Callback. Used to render the meta box HTML
                $screen, //Post type. The post type to add to
                'side', //Context. Where on the screen should this show up? Options: 'normal', 'advanced', or 'side'
                'default' //Priority. Options: 'high', 'core', 'default' or 'low'
            );
        }
    }

    /**
     * Loads a template file which renders the meta box content.
     *
     * Doing this allows us to keep HTML out of the class.
     *
     * @param $post
     */
    public function metabox( $post )
    {
        require_once 'templates/metabox.php';
    }

    /**
     * This function handles saving for the metabox data.
     *
     * @param $post_id
     */
    public function metabox_save( $post_id )
    {
        // Validate first...
        if ( ! self::verify_save('nv_example_meta_box_nonce','save_nv_example_meta_box',$post_id) ) {
            return $post_id;
        }

        // Update the meta field in the database.
        update_post_meta(
            $post_id,
            '_nv_example_meta_field',
            sanitize_text_field( $_POST['example-meta-field'] )
        );
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
    public static function verify_save( $nonce_name, $none_action, $post_id )
    {
        // VALIDATE NONCE & AUTOSAVE
        if ( ! isset( $_POST[$nonce_name] ) ) {
            return false;
        }
        if ( ! wp_verify_nonce( $_POST[$nonce_name], $none_action ) ) {
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
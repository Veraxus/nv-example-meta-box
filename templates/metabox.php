<p>
    <label for="example-field"><?php _e('Example Field:','nvLangScope'); ?> </label>
</p>
<p>
    <input
        type="text"
        id="example-meta-field"
        name="example-meta-field"
        value="<?php echo esc_attr( get_post_meta( $post->ID, '_nv_example_meta_field', true ) ) ?>" />
</p>
<?php wp_nonce_field( 'save_nv_example_meta_box', 'nv_example_meta_box_nonce' ); ?>
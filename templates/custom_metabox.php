<?php
/**
 * CUSTOM META BOX TEMPLATE FILE
 * You can easily hand-craft your own meta boxes if you don't want to use magic ones. In this case note that you can
 * access the metabox id using $args['id'], which is handy for nonces and fetching fields using magic.
 */
?>
<div class="magic-metabox">
	<p>
		<!-- Creating a field manually! -->
		<label for="example-field"><strong><?php _e( 'Example Field', 'nvLangScope' ); ?></strong></label>
		<input
			type="text"
			id="_nv_example_meta_field2"
			name="_nv_example_meta_field2"
			placeholder="<?php _e( "Your value here…", 'nvLangScope' ); ?>"
			value="<?php echo esc_attr( get_post_meta( $post->ID, '_nv_example_meta_field2', true ) ) ?>"/>
	</p>

	<p>
		<!-- A field created using magic! -->
		<?php echo \NV\Plugins\MetaBox::get_field( $args['id'], '_nv_example_meta_dropdown' ); ?>
	</p>

	<!-- Manually create the nonce. First field should be your meta box id, second appends _nonce to your meta box id -->
	<?php wp_nonce_field( $args['id'], $args['id'] . '_nonce' ); ?>
</div>
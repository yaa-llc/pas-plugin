<?php
function tcportfolio_fields_get_meta( $value ) {
	global $post;

	$tcportfolio_field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $tcportfolio_field ) ) {
		return is_array( $tcportfolio_field ) ? stripslashes_deep( $tcportfolio_field ) : stripslashes( wp_kses_decode_entities( $tcportfolio_field ) );
	} else {
		return false;
	}
}

function tcportfolio_fields_add_meta_box() {
	add_meta_box(
		'tcportfolio_fields-tcportfolio-fields',
		__( 'tcportfolio Fields', 'tcportfolio_fields' ),
		'tcportfolio_fields_html',
		'tcportfolio',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'tcportfolio_fields_add_meta_box' );

function tcportfolio_fields_html( $post) {
	wp_nonce_field( '_tcportfolio_fields_nonce', 'tcportfolio_fields_nonce' ); ?>

	<p>
		<label for="tcportfolio_fields_company_name"><?php _e( 'Company Name', 'tcportfolio_fields' ); ?></label><br>
		<input type="text" name="tcportfolio_fields_company_name" id="tcportfolio_fields_company_name" value="<?php echo tcportfolio_fields_get_meta( 'tcportfolio_fields_company_name' ); ?>" size="30" placeholder="themescode">
	</p>	<p>
		<label for="tcportfolio_fields_project_url"><?php _e( 'Project URL', 'tcportfolio_fields' ); ?></label><br>
		<input type="text" name="tcportfolio_fields_project_url" id="tcportfolio_fields_project_url" value="<?php echo tcportfolio_fields_get_meta( 'tcportfolio_fields_project_url' ); ?>" size="30" placeholder="https://themescode.com">
	</p><?php
}

function tcportfolio_fields_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['tcportfolio_fields_nonce'] ) || ! wp_verify_nonce( $_POST['tcportfolio_fields_nonce'], '_tcportfolio_fields_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['tcportfolio_fields_company_name'] ) )
		update_post_meta( $post_id, 'tcportfolio_fields_company_name', esc_attr( $_POST['tcportfolio_fields_company_name'] ) );
	if ( isset( $_POST['tcportfolio_fields_project_url'] ) )
		update_post_meta( $post_id, 'tcportfolio_fields_project_url', esc_attr( $_POST['tcportfolio_fields_project_url'] ) );
}
add_action( 'save_post', 'tcportfolio_fields_save' );

 ?>

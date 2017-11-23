<?php

/************************************
* the code below is just a standard
* options page. Substitute with
* your own.
*************************************/

function wpb_fp_license_menu() {
	add_submenu_page( 
        'edit.php?post_type=wpb_fp_portfolio', 
        __( 'License', WPB_FP_TEXTDOMAIN ),
        __( 'License', WPB_FP_TEXTDOMAIN ),
        'manage_options',
        WPB_FP_PLUGIN_LICENSE_PAGE,
        'wpb_fp_license_page'
    );
}
add_action('admin_menu', 'wpb_fp_license_menu');

function wpb_fp_license_page() {
	$license = get_option( 'wpb_fp_license_key' );
	$status  = get_option( 'wpb_fp_license_status' );
	?>
	<div class="wrap">
		<h2><?php _e('WPB filterable portfolio license activation', WPB_FP_TEXTDOMAIN ); ?></h2>
		<form method="post" action="options.php">

			<?php settings_fields('wpb_fp_license'); ?>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('License Key'); ?>
						</th>
						<td>
							<input id="wpb_fp_license_key" name="wpb_fp_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
							<p class="description"><?php _e('Enter your license key here. Required for getting automatic updates.', WPB_FP_TEXTDOMAIN); ?></p>
						</td>
					</tr>
					<?php if( false !== $license ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('Activate License'); ?>
							</th>
							<td>
								<?php if( $status !== false && $status == 'valid' ) { ?>
									<span style="color:green;"><?php _e('active'); ?></span>
									<?php wp_nonce_field( 'wpb_fp_nonce', 'wpb_fp_nonce' ); ?>
									<input type="submit" class="button-secondary" name="wpb_fp_license_deactivate" value="<?php _e('Deactivate License', WPB_FP_TEXTDOMAIN); ?>"/>
								<?php } else {
									wp_nonce_field( 'wpb_fp_nonce', 'wpb_fp_nonce' ); ?>
									<input type="submit" class="button-secondary" name="wpb_fp_license_activate" value="<?php _e('Activate License', WPB_FP_TEXTDOMAIN); ?>"/>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php submit_button(); ?>

		</form>
	<?php
}

function wpb_fp_register_option() {
	// creates our settings in the options table
	register_setting('wpb_fp_license', 'wpb_fp_license_key', 'wpb_fp_sanitize_license' );
}
add_action('admin_init', 'wpb_fp_register_option');

function wpb_fp_sanitize_license( $new ) {
	$old = get_option( 'wpb_fp_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'wpb_fp_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}



/************************************
* this illustrates how to activate
* a license key
*************************************/

function wpb_fp_activate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['wpb_fp_license_activate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'wpb_fp_nonce', 'wpb_fp_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'wpb_fp_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( WPB_FP_ITEM_NAME ),
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( WPB_FP_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', WPB_FP_TEXTDOMAIN );
			}

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {

				switch( $license_data->error ) {

					case 'expired' :

						$message = sprintf(
							__( 'Your license key expired on %s.', WPB_FP_TEXTDOMAIN ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'revoked' :

						$message = __( 'Your license key has been disabled.', WPB_FP_TEXTDOMAIN );
						break;

					case 'missing' :

						$message = __( 'Invalid license.', WPB_FP_TEXTDOMAIN );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$message = __( 'Your license is not active for this URL.', WPB_FP_TEXTDOMAIN );
						break;

					case 'item_name_mismatch' :

						$message = sprintf( __( 'This appears to be an invalid license key for %s.', WPB_FP_TEXTDOMAIN ), WPB_FP_ITEM_NAME );
						break;

					case 'no_activations_left':

						$message = __( 'Your license key has reached its activation limit.', WPB_FP_TEXTDOMAIN );
						break;

					default :

						$message = __( 'An error occurred, please try again.', WPB_FP_TEXTDOMAIN );
						break;
				}

			}

		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'admin.php?page=' . WPB_FP_PLUGIN_LICENSE_PAGE );
			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		// $license_data->license will be either "valid" or "invalid"

		update_option( 'wpb_fp_license_status', $license_data->license );
		wp_redirect( admin_url( 'admin.php?page=' . WPB_FP_PLUGIN_LICENSE_PAGE ) );
		exit();
	}
}
add_action('admin_init', 'wpb_fp_activate_license');


/***********************************************
* Illustrates how to deactivate a license key.
* This will decrease the site count
***********************************************/

function wpb_fp_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['wpb_fp_license_deactivate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'wpb_fp_nonce', 'wpb_fp_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'wpb_fp_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => urlencode( WPB_FP_ITEM_NAME ),
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( WPB_FP_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', WPB_FP_TEXTDOMAIN );
			}

			$base_url = admin_url( 'admin.php?page=' . WPB_FP_PLUGIN_LICENSE_PAGE );
			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' ) {
			delete_option( 'wpb_fp_license_status' );
		}

		wp_redirect( admin_url( 'admin.php?page=' . WPB_FP_PLUGIN_LICENSE_PAGE ) );
		exit();

	}
}
add_action('admin_init', 'wpb_fp_deactivate_license');


/************************************
* this illustrates how to check if
* a license key is still valid
* the updater does this for you,
* so this is only needed if you
* want to do something custom
*************************************/

function wpb_fp_check_license() {

	global $wp_version;

	$license = trim( get_option( 'wpb_fp_license_key' ) );

	$api_params = array(
		'edd_action' => 'check_license',
		'license' => $license,
		'item_name' => urlencode( WPB_FP_ITEM_NAME ),
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post( WPB_FP_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

	if ( is_wp_error( $response ) )
		return false;

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if( $license_data->license == 'valid' ) {
		echo 'valid'; exit;
		// this license is still valid
	} else {
		echo 'invalid'; exit;
		// this license is no longer valid
	}
}

/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 */
function wpb_fp_admin_notices() {
	if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {

		switch( $_GET['sl_activation'] ) {

			case 'false':
				$message = urldecode( $_GET['message'] );
				?>
				<div class="error">
					<p><?php echo $message; ?></p>
				</div>
				<?php
				break;

			case 'true':
			default:
				// Developers can put a custom success message here for when activation is successful if they way.
				break;

		}
	}
}
add_action( 'admin_notices', 'wpb_fp_admin_notices' );

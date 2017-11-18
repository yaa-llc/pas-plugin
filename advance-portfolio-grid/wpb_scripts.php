<?php

/*
	Advance Portfolio Grid
	By WPBean
	
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


/**
 * Enqueue js files
 */

function wpb_fp_scripts() {
	wp_register_script('wpb-fp-magnific-popup', plugins_url('/assets/js/jquery.magnific-popup.min.js', __FILE__),array('jquery'),'1.0', false);
	wp_enqueue_script('wpb-fp-magnific-popup');	
}
add_action( 'wp_enqueue_scripts', 'wpb_fp_scripts' ); 


/**
 * Enqueue css files
 */

function wpb_fp_styles() {
	wp_enqueue_style('wpb-fp-font', '//fonts.googleapis.com/css?family=Open+Sans','','1.0');
	wp_enqueue_style('wpb-fp-bootstrap-grid', plugins_url('/assets/css/wpb-custom-bootstrap.css', __FILE__),'','3.2');
	wp_enqueue_style('wpb-fp-main', plugins_url('/assets/css/main.css', __FILE__),'','1.0');
	wp_enqueue_style('wpb-fp-font-awesome', plugins_url('/assets/css/font-awesome.min.css', __FILE__),'','4.2.0');
	wp_enqueue_style('wpb-fp-magnific-popup', plugins_url('/assets/css/magnific-popup.css', __FILE__),'','1.0');
	wp_enqueue_style('wpb-fp-hover-effects', plugins_url('/assets/css/hover-effects.css', __FILE__),'','1.0');
}
add_action( 'wp_enqueue_scripts', 'wpb_fp_styles' );



/**
 * Adding Custom styles
 */

add_action( 'wpb_fp_after_portfolio','wpb_fp_custom_style' );
function wpb_fp_custom_style(){
	$wpb_fp_custom_style = wpb_fp_get_option( 'wpb_fp_custom_css_', 'wpb_fp_style', '' );
	if( isset($wpb_fp_custom_style) && !empty($wpb_fp_custom_style) ){
		?>
			<style type="text/css">
			<?php echo $wpb_fp_custom_style;?>
			</style>
		<?php
	}
}


/**
 * Enqueue CSS For Admin
 */

function wpb_fp_admin_adding_style() {
	$screen = get_current_screen();
	$wpb_post_type_select = wpb_fp_get_option( 'wpb_post_type_select_', 'wpb_fp_advanced', 'wpb_fp_portfolio' );

	if( $screen->id == 'wpb_fp_portfolio_page_portfolio-settings' || $screen->id == $wpb_post_type_select ){
		wp_enqueue_style('wpb_wrps_admin_style', plugins_url('/assets/css/admin-style.css', __FILE__),'','1.0', false);
	}
}
add_action( 'admin_enqueue_scripts', 'wpb_fp_admin_adding_style',11 );
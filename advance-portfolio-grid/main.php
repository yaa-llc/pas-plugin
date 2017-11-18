<?php 
/**
Plugin Name: Advance Portfolio Grid 
Plugin URI: http://wpbean.com/plugins/
Description: Advance Portfolio Grid, a highly customizable most advance portfolio plugin for WordPress. Use this shortcode [wpb-portfolio]
Author: wpbean
Version: 1.03.3
Author URI: http://wpbean.com
text-domain: wpb_fp
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


/**
 * Internationalization
 */

function wpb_fp_textdomain() {
	load_plugin_textdomain( 'wpb_fp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'wpb_fp_textdomain' );



/**
 * Add plugin action links
 */

function wpb_portfolio_plugin_actions( $links ) {
   $links[] = '<a href="'.menu_page_url('portfolio-settings', false).'">'. __('Settings','wpb_fp') .'</a>';
   $links[] = '<a href="http://wpbean.com/support/" target="_blank">'. __('Support','wpb_fp') .'</a>';
   return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpb_portfolio_plugin_actions' );



/**
 * Plugin Activation redirect 
 */

if( !function_exists( 'wpb_fp_activation_redirect' ) ){
	function wpb_fp_activation_redirect( $plugin ) {
	    if( $plugin == plugin_basename( __FILE__ ) ) {
	        exit( wp_redirect( admin_url( 'edit.php?post_type=wpb_fp_portfolio&page=portfolio-settings' ) ) );
	    }
	}
}
add_action( 'activated_plugin', 'wpb_fp_activation_redirect' );


/**
 * Requred files 
 */

require_once dirname( __FILE__ ) . '/wpb_scripts.php'; // CSS & JS file enqueue script
require_once dirname( __FILE__ ) . '/wpb-fp-shortcode.php'; // shortcode
require_once dirname( __FILE__ ) . '/admin/wpb-fp-getting-options.php'; // Gettings options
require_once dirname( __FILE__ ) . '/wpb-fp-post-type.php'; // Portfolio Post Type
require_once dirname( __FILE__ ) . '/admin/wpb_aq_resizer.php'; // Image Resizer
require_once dirname( __FILE__ ) . '/admin/wpb-fp-admin.php'; // Admin releted scripts
require_once dirname( __FILE__ ) . '/admin/wpb-class.settings-api.php'; // Settings Api class by Wedevs
require_once dirname( __FILE__ ) . '/admin/wpb-settings-config.php'; // Settings Config
require_once dirname( __FILE__ ) . '/wpb-portfolio.php';
require_once dirname( __FILE__ ) . '/wpb_fp_metabox.php'; // Custom Metabox for portfolio
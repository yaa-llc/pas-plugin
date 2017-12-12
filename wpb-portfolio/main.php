<?php 
/**
Plugin Name: WPB Filterable Portfolio
Plugin URI: https://wpbean.com/product/wpb-filterable-portfolio
Description: Filterable portfolio Wordpress plugin. Shortcode [wpb-portfolio]
Author: WpBean
Version: 2.2.3.1
Author URI: https://wpbean.com
text-domain: wpb_fp
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/**
 * WpBean Plugin updater init
 * Warning!!!! 
 * Do not make any change in the code bellow. It will process the plugin auto update.
 */

define( 'WPB_FP_VERSION', '2.2.3.1' );
define( 'WPB_FP_STORE_URL', 'https://wpbean.com' );
define( 'WPB_FP_ITEM_NAME', 'WPB Filterable Portfolio' );
define( 'WPB_FP_PLUGIN_LICENSE_PAGE', 'wpb-filterable-portfolio-license' );

function wpb_fp_plugin_updater_init() {

	$license_key = trim( get_option( 'wpb_fp_license_key' ) );

	$edd_updater = new WpBean_Plugin_Updater( WPB_FP_STORE_URL, __FILE__, array(
			'version'   => WPB_FP_VERSION,
			'license'   => $license_key,
			'item_name' => WPB_FP_ITEM_NAME,
			'author'    => 'WpBean',
			'url'       => home_url()
		)
	);

}
add_action( 'admin_init', 'wpb_fp_plugin_updater_init', 0 );



/**
 * Define Path 
 */

if ( !defined( 'WPB_FP_URI' ) ) {
	define( 'WPB_FP_URI', plugin_dir_path( __FILE__ ) );
}


/**
 * Define metaboxes directory constant
 */

if ( !defined( 'WPB_FP_CUSTOM_METABOXES_DIR' ) ) {
	define( 'WPB_FP_CUSTOM_METABOXES_DIR', plugins_url('/admin/metaboxes', __FILE__) );
}


/**
 * Define TextDomain
 */

if ( !defined( 'WPB_FP_TEXTDOMAIN' ) ) {
	define( 'WPB_FP_TEXTDOMAIN','wpb_fp' );
}



/**
 * Internationalization
 */

function wpb_fp_textdomain() {
	load_plugin_textdomain( WPB_FP_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'wpb_fp_textdomain' );



/**
 * Add plugin action links
 */

function wpb_portfolio_plugin_actions( $links ) {
   $links[] = '<a href="'.menu_page_url('portfolio-settings', false).'">'. __('Settings',WPB_FP_TEXTDOMAIN) .'</a>';
   $links[] = '<a href="http://wpbean.com/support/" target="_blank">'. __('Support',WPB_FP_TEXTDOMAIN) .'</a>';
   $links[] = '<a href="http://wpbean.com/wpb-filterable-portfolio-documentation/" target="_blank">'. __('Documentation',WPB_FP_TEXTDOMAIN) .'</a>';
   return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpb_portfolio_plugin_actions' );




/**
 * Requred files
 */

require_once dirname( __FILE__ ) . '/admin/wpb-fp-getting-options.php';
require_once dirname( __FILE__ ) . '/admin/wpb_aq_resizer.php';
require_once dirname( __FILE__ ) . '/admin/wpb-fp-admin.php';
require_once dirname( __FILE__ ) . '/admin/wpb-class.settings-api.php';
require_once dirname( __FILE__ ) . '/admin/wpb-settings-config.php';
require_once dirname( __FILE__ ) . '/admin/metaboxes/meta_box.php';
require_once dirname( __FILE__ ) . '/admin/wpb_fp_metabox_conig.php';
require_once dirname( __FILE__ ) . '/admin/wpb_fp_shortcode_generator.php';


require_once dirname( __FILE__ ) . '/inc/wpb_scripts.php';
require_once dirname( __FILE__ ) . '/inc/wpb-fp-shortcode.php';
require_once dirname( __FILE__ ) . '/inc/wpb-fp-post-type.php';
require_once dirname( __FILE__ ) . '/inc/wpb-fp-functions.php';

if( !class_exists( 'WpBean_Plugin_Updater' ) ) {
	include( dirname( __FILE__ ) . '/admin/updater/plugin-updater.php' );
}
require_once dirname( __FILE__ ) . '/admin/updater/updater-init.php';


/**
 * Gallery
 */

$wpb_fp_gallery_support = wpb_fp_get_option( 'wpb_fp_gallery_support', 'wpb_fp_general' );
if( $wpb_fp_gallery_support != 'on' ){
	require_once dirname( __FILE__ ) . '/inc/wpb_fp_gallery.php';
}
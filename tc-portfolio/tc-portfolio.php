<?php
/**
 * Plugin Name:		   TC Portfolio
 * Plugin URI:		   https://www.themescode.com/items/
 * Description:		   Portfolio is a custom post type based  Responsive Filterable Portfolio showing plugin.Users can create stunning portfolio WordPress site usining Shortcode [tc-portfolio].TC Portfolio is  a user-friendly, fully responsive , filterable portfolio showcasing plugin.
 * Version: 		   1.3
 * Author: 			   themesCode 
 * Author URI: 		   https://www.themescode.com/items/tc-portfolio-pro/
 * Text Domain:        tc-portfolio
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 * License: GPL2
 */

 /**
  * Protect direct access
  */

 if( ! defined( 'TC_PORTFOLIO_HACK_MSG' ) ) define( 'TC_PORTFOLIO_HACK_MSG', __( 'Sorry ! You made a mistake !', 'tc-portfolio' ) );
 if ( ! defined( 'ABSPATH' ) ) die( TC_PORTFOLIO_HACK_MSG );

 /**
  * Defining constants
 */

 if( ! defined( 'TCPORTFOLIO_PLUGIN_DIR' ) ) define( 'TCPORTFOLIO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
 if( ! defined( 'TCPORTFOLIO_PLUGIN_URI' ) ) define( 'TCPORTFOLIO_PLUGIN_URI', plugins_url( '', __FILE__ ) );

// require files
// require files
require_once dirname( __FILE__ ) . '/lib/class.settings-api.php';
require_once dirname( __FILE__ ) . '/lib/tc-portfolio-settings.php';

new themesCode_Settings_API_Test();

require_once TCPORTFOLIO_PLUGIN_DIR .'/lib/tc-portfolio-cpt.php';
require_once TCPORTFOLIO_PLUGIN_DIR .'/lib/tc-metabox.php';
require_once TCPORTFOLIO_PLUGIN_DIR .'/public/tc-view.php';
require_once TCPORTFOLIO_PLUGIN_DIR .'/lib/tcp-class.php';
require_once TCPORTFOLIO_PLUGIN_DIR .'/lib/tc-portfolio-column.php';

 function tcportfolio_faq_enqueue_scripts() {
    //Plugin Main CSS File
     wp_enqueue_script('isotope', TCPORTFOLIO_PLUGIN_URI.'/vendors/isotope/isotope.pkgd.min.js', array('jquery'),'3.1.0', true);
     wp_enqueue_style('tc-portfolio', TCPORTFOLIO_PLUGIN_URI.'/assets/css/tc-portfolio-style.css');
     wp_enqueue_style( 'font-awesome','//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
     wp_enqueue_style('magnific-popup', TCPORTFOLIO_PLUGIN_URI.'/vendors/magnific-popup/magnific-popup.css');
    wp_enqueue_script('magnific-popup', TCPORTFOLIO_PLUGIN_URI.'/vendors/magnific-popup/jquery.magnific-popup.min.js', array('jquery'), 1.12, true);
     wp_enqueue_script('tc-custom', TCPORTFOLIO_PLUGIN_URI.'/assets/js/tc-custom.js');
  }
 add_action( 'wp_enqueue_scripts', 'tcportfolio_faq_enqueue_scripts' );

 function tcportfolio_admin_style() {
  wp_enqueue_style( 'tcportfolio-admin', TCPORTFOLIO_PLUGIN_URI.'/assets/css/tc-portfolio-admin.css');
 }
 add_action( 'admin_enqueue_scripts', 'tcportfolio_admin_style' );

 // Sub Menu Page

 add_action('admin_menu', 'tcportfolio_menu_init');
 function tcportfolio_menu_help(){
   include('lib/tc-portfolio-help-upgrade.php');
 }
 function tcportfolio_menu_init()
   {
     add_submenu_page('edit.php?post_type=tcportfolio', __('Help & Upgrade','tc-portfolio'), __('Help & Upgrade','tc-portfolio'), 'manage_options', 'tcportfolio_menu_help', 'tcportfolio_menu_help');
   }


/* Move Featured Image Below Title */

require_once TCPORTFOLIO_PLUGIN_DIR .'/lib/class-featured-image-metabox-cusomizer.php';

new Featured_Image_Metabox_Customizer(array(
'post_type'     => 'tcportfolio',
'metabox_title' => __( 'Portfolio Image', 'tcpc' ),
'set_text'      => __( 'Add Portfolio Image', 'tcpc' ),
'remove_text'   => __( 'Remove Portfolio Image', 'tcpc' )
));

add_filter( 'gettext', 'tcp_excerpt_sd', 10, 2 );
function tcp_excerpt_sd( $translation, $original )
{
    if ( 'Excerpt' == $original ) {
        return 'Short Description';
    }else{
        $pos = strpos($original, 'Excerpts are optional hand-crafted summaries of your');
        if ($pos !== false) {
            return  'This Portfolio Short Description will appear on Image hover . You can hide/show through Basic Settings';
        }
    }
    return $translation;
}


// After Plugin Activation redirect

 if( !function_exists( 'tcportfolio_activation_redirect' ) ){
   function tcportfolio_activation_redirect( $plugin ) {
       if( $plugin == plugin_basename( __FILE__ ) ) {
           exit( wp_redirect( admin_url( 'edit.php?post_type=tcportfolio&page=tcportfolio_menu_help' ) ) );
       }
   }
 }
 add_action( 'activated_plugin', 'tcportfolio_activation_redirect' );


// adding link
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'tcportfolio_plugin_action_links' );

function tcportfolio_plugin_action_links( $links ) {
   $links[] = '<a class="tc-pro-link" href="https://www.themescode.com/items/tc-portfolio-pro/" target="_blank">Go Pro! </a>';
   $links[] = '<a href="https://www.themescode.com/items/category/wordpress-plugins" target="_blank">TC Plugins</a>';
   return $links;
}

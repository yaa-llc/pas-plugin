<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.huebscheseiten.de
 * @since             1.0.0
 * @package           Jub_Portfolio_Gallery
 *
 * @wordpress-plugin
 * Plugin Name:       Filterable Portfolio
 * Plugin URI:        http://www.jb-portfolio.com
 * Description:       Jung&Billig Portfolio Gallery is an easy to use post type based responsive Portfolio plugin, offering a wide range of customization options.


 * Version:           1.1
 * Author:            Jan Müller
 * Author URI:        http://www.jungundbillig.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       jub-portfolio-gallery
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-jub-portfolio-gallery-activator.php
 */
function activate_jub_portfolio_gallery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jub-portfolio-gallery-activator.php';
	Jub_Portfolio_Gallery_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-jub-portfolio-gallery-deactivator.php
 */
function deactivate_jub_portfolio_gallery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jub-portfolio-gallery-deactivator.php';
	Jub_Portfolio_Gallery_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_jub_portfolio_gallery' );
register_deactivation_hook( __FILE__, 'deactivate_jub_portfolio_gallery' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-jub-portfolio-gallery.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_jub_portfolio_gallery() {

	$plugin = new Jub_Portfolio_Gallery();
	$plugin->run();

}
run_jub_portfolio_gallery();

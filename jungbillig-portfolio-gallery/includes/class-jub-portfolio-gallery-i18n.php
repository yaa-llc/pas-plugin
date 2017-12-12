<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.huebscheseiten.de
 * @since      1.0.0
 *
 * @package    Jub_Portfolio_Gallery
 * @subpackage Jub_Portfolio_Gallery/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Jub_Portfolio_Gallery
 * @subpackage Jub_Portfolio_Gallery/includes
 * @author     Jan Müller <jan.m@jungundbillig.de>
 */
class Jub_Portfolio_Gallery_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'jub-portfolio-gallery',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}

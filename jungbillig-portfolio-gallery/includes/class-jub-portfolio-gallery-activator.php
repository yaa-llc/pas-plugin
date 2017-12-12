<?php

/**
 * Fired during plugin activation
 *
 * @link       www.huebscheseiten.de
 * @since      1.0.0
 *
 * @package    Jub_Portfolio_Gallery
 * @subpackage Jub_Portfolio_Gallery/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Jub_Portfolio_Gallery
 * @subpackage Jub_Portfolio_Gallery/includes
 * @author     Jan Müller <jan.m@jungundbillig.de>
 */
class Jub_Portfolio_Gallery_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-jub-portfolio-gallery-admin.php';


        Jub_Portfolio_Gallery_Admin::init_portfolio_post_type();
        Jub_Portfolio_Gallery_Admin::init_jubportfolio_taxonomy();

        flush_rewrite_rules();

        $opts 		= array();
        $options 	= Jub_Portfolio_Gallery_Admin::get_options_list();


        foreach ( $options as $option ) {
            $opts[ $option[0] ] = $option[2];
        }

        update_option( 'jub-portfolio-gallery-options', $opts );
	}

}

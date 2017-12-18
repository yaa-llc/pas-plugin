<?php
/*
Plugin Name: Locatoraid
Plugin URI: http://www.locatoraid.com/
Description: Store locator plugin
Version: 3.3.1
Author: hitcode.com
Author URI: http://www.locatoraid.com/
Text Domain: locatoraid
Domain Path: /languages/
*/

if (! defined('ABSPATH')) exit; // Exit if accessed directly

if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	add_action( 'admin_notices', create_function( '', "echo '<div class=\"error\"><p>".__('Locatoraid requires PHP 5.3 to function properly. Please upgrade PHP or deactivate Locatoraid.', 'locatoraid') ."</p></div>';" ) );
	return;
}

if( file_exists(dirname(__FILE__) . '/db.php') ){
	$nts_no_db = TRUE;
	include_once( dirname(__FILE__) . '/db.php' );
	$happ_path = NTS_DEVELOPMENT2;
}
else {
	$happ_path = dirname(__FILE__) . '/happ2';
}

include_once( $happ_path . '/lib-wp/hcWpBase6.php' );

class Locatoraid extends hcWpBase6
{
	public function __construct()
	{
		parent::__construct(
			array('locatoraid', 'lc'),	// app
			__FILE__,	// path,
			'',			// hc product,
			'locatoraid',	// slug
			'lctr2'		// db prefix
			);

		add_action(	'init', array($this, '_this_init') );
	}

	public function _this_init()
	{
		$this->hcapp_start();
	}
}

$hcsl = new Locatoraid();

$widget_file = dirname(__FILE__) . '/modules/widget.wordpress/widget_searchform.php';
if( file_exists($widget_file) ){
	include_once( $widget_file );
	add_action( 'widgets_init', create_function( '', 'register_widget( "Locatoraid_Searchform_Widget30" );' ) );
}
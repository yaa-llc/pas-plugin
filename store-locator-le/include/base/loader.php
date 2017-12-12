<?php

/**
 * Check if it is ok to load SLP.
 * PHP min: 5.2 pathinfo() with path_parts['filename']  @see  http://php.net/manual/en/function.pathinfo.php
 * PHP min: 5.3 get_called_class() @see http://php.net/manual/en/function.get-called-class.php
 *
 * @return bool
 */
function slp_passed_requirements(){
	$min_wp_version = '3.8';
	$min_php_version = '5.3';

	// Check WP Version
	//
	global $wp_version;
	if ( version_compare( $wp_version, $min_wp_version, '<' ) ) {
		add_action(
			'admin_notices',
			create_function(
				'',
				"echo '<div class=\"error\"><p>" .
				sprintf(
					__( '%s requires WordPress %s to function properly. ', 'store-locator-le' ),
					__( 'Store Locator Plus', 'store-locator-le' ),
					$min_wp_version
				) .
				__( 'This plugin has been deactivated.', 'store-locator-le' ) .
				__( 'Please upgrade WordPress.', 'store-locator-le' ) .
				"</p></div>';"
			)
		);
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
		return false;
	}

	// Check PHP Version
	//
	if ( version_compare( PHP_VERSION, $min_php_version, '<' ) ) {
		add_action(
			'admin_notices',
			create_function(
				'',
				"echo '<div class=\"error\"><p>" .
				sprintf(
					__( '%s requires PHP %s to function properly. ', 'store-locator-le' ),
					__( 'Store Locator Plus', 'store-locator-le' ),
					$min_php_version
				) .
				__( 'This plugin has been deactivated.', 'store-locator-le' ) .
				__( 'Consider upgrading PHP.', 'store-locator-le' ) .
				__( 'Version 5.2 has not been maintained and supported by PHP since January 2011.', 'store-locator-le' ) .
				"</p></div>';"
			)
		);
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
		return false;
	}

	return true;
}

/**
 * Setup the SLP Environment (defines, etc.)
 */
function slp_setup_environment() {
	$slp_loader_file = 'store-locator-le/store-locator-le.php';
	if ( isset( $mu_plugin ) && ( strpos( $mu_plugin, $slp_loader_file ) !== false ) ) {
		$slp_plugin_file = $mu_plugin;
	} elseif ( isset( $plugin ) && ( strpos( $plugin, $slp_loader_file ) !== false ) ) {
		$slp_plugin_file = $plugin;
	} elseif ( isset( $network_plugin ) && ( strpos( $network_plugin, $slp_loader_file ) !== false ) ) {
		$slp_plugin_file = $network_plugin;
	} else {
		$slp_plugin_file = SLP_LOADER_FILE;
	}

// Test that the SLP file is in MU directory.
	if ( strpos( $slp_plugin_file, WPMU_PLUGIN_DIR ) !== false ) {
		$slp_dir = WPMU_PLUGIN_DIR;
	} else {
		$slp_dir = WP_PLUGIN_DIR;
	}

	if ( defined( 'SLPLUS_FILE' ) === false ) {
		if ( file_exists( WPMU_PLUGIN_DIR . '/' . basename( dirname( $slp_plugin_file ) ) . '/store-locator-le.php' ) ) {
			define( 'SLPLUS_FILE', $slp_plugin_file );
		} elseif ( file_exists( WP_PLUGIN_DIR . '/' . basename( dirname( $slp_plugin_file ) ) . '/store-locator-le.php' ) ) {
			define( 'SLPLUS_FILE', $slp_plugin_file );
		} else {
			define( 'SLPLUS_FILE', SLP_LOADER_FILE );
		}
	}

	if ( defined( 'SLPLUS_PLUGINDIR' ) === false ) define( 'SLPLUS_PLUGINDIR'  , $slp_dir . '/' . basename( dirname( SLPLUS_FILE ) ) . '/'  );
	if ( defined( 'SLPLUS_ICONDIR'   ) === false ) define( 'SLPLUS_ICONDIR'    , SLPLUS_PLUGINDIR . 'images/icons/'                         ); // Path to the icon images

	if ( defined( 'SLPLUS_PLUGINURL' ) === false ) define( 'SLPLUS_PLUGINURL'  , plugins_url( '' , SLPLUS_FILE )                            ); // Fully qualified URL to this plugin directory.
	if ( defined( 'SLPLUS_ICONURL'   ) === false ) define( 'SLPLUS_ICONURL'    , SLPLUS_PLUGINURL . '/images/icons/'                        ); // Fully qualified URL to the icon images.
	if ( defined( 'SLPLUS_COREURL'   ) === false ) define( 'SLPLUS_COREURL'    , SLPLUS_PLUGINURL                                           );

	if ( defined( 'SLPLUS_BASENAME'  ) === false ) define( 'SLPLUS_BASENAME'   , plugin_basename( SLPLUS_FILE )                             ); // The relative path from the plugins directory

	if (defined('SLPLUS_UPLOADDIR'   ) === false) {
		$upload_dir = wp_upload_dir('slp');
		$error = $upload_dir['error'];
		if (empty($error)) {
			define('SLPLUS_UPLOADDIR', $upload_dir['path']);
			define('SLPLUS_UPLOADURL', $upload_dir['url']);
		} else {
			$error = preg_replace(
				'/Unable to create directory /',
				'Unable to create directory ' . ABSPATH ,
				$error
			);
			add_action(
				'admin_notices',
				create_function(
					'',
					"echo '<div class=\"error\"><p>".
					__( 'Store Locator Plus upload directory error.' , 'store-locator-le' ) .
					$error .
					"</p></div>';"
				)
			);
			define('SLPLUS_UPLOADDIR', SLPLUS_PLUGINDIR);
			define('SLPLUS_UPLOADURL', SLPLUS_PLUGINURL);
		}
	}

	if ( defined( 'SLPLUS_NAME'      ) === false ) define( 'SLPLUS_NAME'        , __( 'Store Locator Plus', 'store-locator-le' )            );
	if ( defined( 'SLPLUS_PREFIX'    ) === false ) define( 'SLPLUS_PREFIX'      , 'csl-slplus'                                              );
	if ( defined( 'SLP_ADMIN_PAGEPRE') === false ) define( 'SLP_ADMIN_PAGEPRE'  , 'store-locator-plus_page_'                                );
}
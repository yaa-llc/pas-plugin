<?php

/*
	WPB Filterable Portfolio
	By WPBean
	
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


/**
 * Register Custom Post Type for portfolio
 */

add_action( 'init', 'wpb_fp_post_type', 0 );

if ( ! function_exists('wpb_fp_post_type') ) {
	function wpb_fp_post_type() {

		$labels = array(
			'name'                => _x( 'Portfolios', 'Post Type General Name', WPB_FP_TEXTDOMAIN ),
			'singular_name'       => _x( 'Portfolio', 'Post Type Singular Name', WPB_FP_TEXTDOMAIN ),
			'menu_name'           => __( 'Portfolio', WPB_FP_TEXTDOMAIN ),
			'parent_item_colon'   => __( 'Parent Portfolio:', WPB_FP_TEXTDOMAIN ),
			'all_items'           => __( 'All Portfolios', WPB_FP_TEXTDOMAIN ),
			'view_item'           => __( 'View Portfolio', WPB_FP_TEXTDOMAIN ),
			'add_new_item'        => __( 'Add New Portfolio', WPB_FP_TEXTDOMAIN ),
			'add_new'             => __( 'Add New', WPB_FP_TEXTDOMAIN ),
			'edit_item'           => __( 'Edit Portfolio', WPB_FP_TEXTDOMAIN ),
			'update_item'         => __( 'Update Portfolio', WPB_FP_TEXTDOMAIN ),
			'search_items'        => __( 'Search Portfolio', WPB_FP_TEXTDOMAIN ),
			'not_found'           => __( 'Not found', WPB_FP_TEXTDOMAIN ),
			'not_found_in_trash'  => __( 'Not found in Trash', WPB_FP_TEXTDOMAIN ),
		);

		$wpb_fp_portfolio_slug = wpb_fp_get_option( 'wpb_fp_portfolio_slug_', 'wpb_fp_advanced', 'portfolio' );

		$rewrite = array(
			'slug'                => $wpb_fp_portfolio_slug,
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);
		$args = array(
			'label'               => __( 'Portfolio', WPB_FP_TEXTDOMAIN ),
			'description'         => __( 'WPB Filterable Portfolio plugin post type', WPB_FP_TEXTDOMAIN ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', ),
			'taxonomies'          => array( 'wpb_fp_portfolio_cat' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-portfolio',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'page',
		);

		$args = apply_filters( 'wpb_fp_portfolio_post_type_args', $args );

		register_post_type( 'wpb_fp_portfolio', $args );

	}
}



/**
 * Feature Image Support
 */

if ( ! function_exists('wpb_fp_theme_support') ) {
	function wpb_fp_theme_support()  {
		add_theme_support( 'post-thumbnails', array( 'wpb_fp_portfolio' ) );
	}
}
add_action( 'after_setup_theme', 'wpb_fp_theme_support' );



/**
 * Register Custom Taxonomy for Portfolio
 */

add_action( 'init', 'wpb_fp_taxonomy', 0 );

if ( ! function_exists( 'wpb_fp_taxonomy' ) ) {
	function wpb_fp_taxonomy() {

		$labels = array(
			'name'                       => _x( 'Portfolio Categories', 'Taxonomy General Name', WPB_FP_TEXTDOMAIN ),
			'singular_name'              => _x( 'Portfolio Category', 'Taxonomy Singular Name', WPB_FP_TEXTDOMAIN ),
			'menu_name'                  => __( 'Portfolio Category', WPB_FP_TEXTDOMAIN ),
			'all_items'                  => __( 'All Categories', WPB_FP_TEXTDOMAIN ),
			'parent_item'                => __( 'Parent Category', WPB_FP_TEXTDOMAIN ),
			'parent_item_colon'          => __( 'Parent Category:', WPB_FP_TEXTDOMAIN ),
			'new_item_name'              => __( 'New Category Name', WPB_FP_TEXTDOMAIN ),
			'add_new_item'               => __( 'Add New Category', WPB_FP_TEXTDOMAIN ),
			'edit_item'                  => __( 'Edit Category', WPB_FP_TEXTDOMAIN ),
			'update_item'                => __( 'Update Category', WPB_FP_TEXTDOMAIN ),
			'separate_items_with_commas' => __( 'Separate categories with commas', WPB_FP_TEXTDOMAIN ),
			'search_items'               => __( 'Search categories', WPB_FP_TEXTDOMAIN ),
			'add_or_remove_items'        => __( 'Add or remove Categories', WPB_FP_TEXTDOMAIN ),
			'choose_from_most_used'      => __( 'Choose from the most used categories', WPB_FP_TEXTDOMAIN ),
			'not_found'                  => __( 'Not Found', WPB_FP_TEXTDOMAIN ),
		);
		$rewrite = array(
			'slug'                       => 'portfolio-category',
			'with_front'                 => true,
			'hierarchical'               => false,
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
			'rewrite'                    => $rewrite,
		);
		register_taxonomy( 'wpb_fp_portfolio_cat', array( 'wpb_fp_portfolio' ), $args );

	}
}
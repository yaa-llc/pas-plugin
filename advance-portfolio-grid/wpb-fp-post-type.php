<?php

/*
	Advance Portfolio Grid
	By WPBean
	
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


// Register Custom Post Type for portfolio
	
if ( ! function_exists('wpb_fp_post_type') ) {
function wpb_fp_post_type() {

	$labels = array(
		'name'                => _x( 'Portfolios', 'Post Type General Name', 'wpb_fp' ),
		'singular_name'       => _x( 'Portfolio', 'Post Type Singular Name', 'wpb_fp' ),
		'menu_name'           => __( 'Portfolio', 'wpb_fp' ),
		'parent_item_colon'   => __( 'Parent Portfolio:', 'wpb_fp' ),
		'all_items'           => __( 'All Portfolios', 'wpb_fp' ),
		'view_item'           => __( 'View Portfolio', 'wpb_fp' ),
		'add_new_item'        => __( 'Add New Portfolio', 'wpb_fp' ),
		'add_new'             => __( 'Add New', 'wpb_fp' ),
		'edit_item'           => __( 'Edit Portfolio', 'wpb_fp' ),
		'update_item'         => __( 'Update Portfolio', 'wpb_fp' ),
		'search_items'        => __( 'Search Portfolio', 'wpb_fp' ),
		'not_found'           => __( 'Not found', 'wpb_fp' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'wpb_fp' ),
	);
	$rewrite = array(
		'slug'                => 'portfolio',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);
	$args = array(
		'label'               => __( 'Portfolio', 'wpb_fp' ),
		'description'         => __( 'Portfolio Grid plugin post type', 'wpb_fp' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', ),
		'taxonomies'          => array( 'wpb_fp_portfolio_cat' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 75,
		'menu_icon'           => 'dashicons-portfolio',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
	);
	register_post_type( 'wpb_fp_portfolio', $args );

}

// Hook into the 'init' action
add_action( 'init', 'wpb_fp_post_type', 0 );

}



// Register Theme Features (feature image for portfolio)

if ( ! function_exists('wpb_fp_theme_support') ) {

function wpb_fp_theme_support()  {

	// Add theme support for Featured Images
	add_theme_support( 'post-thumbnails', array( 'wpb_fp_portfolio' ) );
}

// Hook into the 'after_setup_theme' action
add_action( 'after_setup_theme', 'wpb_fp_theme_support' );

}


// Register Custom Taxonomy for Portfolio

if ( ! function_exists( 'wpb_fp_taxonomy' ) ) {
function wpb_fp_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Portfolio Categories', 'Taxonomy General Name', 'wpb_fp' ),
		'singular_name'              => _x( 'Portfolio Category', 'Taxonomy Singular Name', 'wpb_fp' ),
		'menu_name'                  => __( 'Portfolio Category', 'wpb_fp' ),
		'all_items'                  => __( 'All Categories', 'wpb_fp' ),
		'parent_item'                => __( 'Parent Category', 'wpb_fp' ),
		'parent_item_colon'          => __( 'Parent Category:', 'wpb_fp' ),
		'new_item_name'              => __( 'New Category Name', 'wpb_fp' ),
		'add_new_item'               => __( 'Add New Category', 'wpb_fp' ),
		'edit_item'                  => __( 'Edit Category', 'wpb_fp' ),
		'update_item'                => __( 'Update Category', 'wpb_fp' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'wpb_fp' ),
		'search_items'               => __( 'Search categories', 'wpb_fp' ),
		'add_or_remove_items'        => __( 'Add or remove Categories', 'wpb_fp' ),
		'choose_from_most_used'      => __( 'Choose from the most used categories', 'wpb_fp' ),
		'not_found'                  => __( 'Not Found', 'wpb_fp' ),
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

// Hook into the 'init' action
add_action( 'init', 'wpb_fp_taxonomy', 0 );

}
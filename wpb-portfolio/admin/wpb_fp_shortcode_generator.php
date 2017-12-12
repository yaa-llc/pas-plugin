<?php

/*
	WPB Filterable Portfolio
	By WPBean
	
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 




/**
 * Shortcode Generator
 */

function wpb_fp_shortcode_generate_post_type() {

	$labels = array(
		'name'                => _x( 'Portfolio Shortcodes', 'Post Type General Name', WPB_FP_TEXTDOMAIN ),
		'singular_name'       => _x( 'Portfolio Shortcode', 'Post Type Singular Name', WPB_FP_TEXTDOMAIN ),
		'menu_name'           => __( 'Portfolio Shortcode', WPB_FP_TEXTDOMAIN ),
		'name_admin_bar'      => __( 'Portfolio Shortcode', WPB_FP_TEXTDOMAIN ),
		'parent_item_colon'   => __( 'Parent Portfolio Shortcode:', WPB_FP_TEXTDOMAIN ),
		'all_items'           => __( 'Portfolio Shortcodes', WPB_FP_TEXTDOMAIN ),
		'add_new_item'        => __( 'Add New Portfolio Shortcode', WPB_FP_TEXTDOMAIN ),
		'add_new'             => __( 'Add New Shortcode', WPB_FP_TEXTDOMAIN ),
		'new_item'            => __( 'New Portfolio Shortcode', WPB_FP_TEXTDOMAIN ),
		'edit_item'           => __( 'Edit Portfolio Shortcode', WPB_FP_TEXTDOMAIN ),
		'update_item'         => __( 'Update Portfolio Shortcode', WPB_FP_TEXTDOMAIN ),
		'view_item'           => __( 'View Portfolio Shortcode', WPB_FP_TEXTDOMAIN ),
		'search_items'        => __( 'Search Portfolio Shortcode', WPB_FP_TEXTDOMAIN ),
		'not_found'           => __( 'Not found', WPB_FP_TEXTDOMAIN ),
		'not_found_in_trash'  => __( 'Not found in Trash', WPB_FP_TEXTDOMAIN ),
	);
	$args = array(
		'label'               => __( 'Portfolio Shortcode', WPB_FP_TEXTDOMAIN ),
		'description'         => __( 'Post Type For Portfolio Shortcode Generator ', WPB_FP_TEXTDOMAIN ),
		'labels'              => $labels,
		'supports'            => array( 'title', ),
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => 'edit.php?post_type=wpb_fp_portfolio',
		'menu_position'       => 5,
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,		
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'page',
	);
	register_post_type( 'wpb_fp_shortcode_gen', $args );

}
add_action( 'init', 'wpb_fp_shortcode_generate_post_type', 0 );


/**
 * Post Type Title for Portfolio Shortcode 
 */

function wpb_fp_change_default_title_placeholder( $title ){
    $screen = get_current_screen();
    if ( 'wpb_fp_shortcode_gen' == $screen->post_type ){
        $title = __( 'Shortcode Name',WPB_FP_TEXTDOMAIN );
    }
    return $title;
}
add_filter( 'enter_title_here', 'wpb_fp_change_default_title_placeholder' );


/**
 * Post Type Title for Portfolio Shortcode 
 */

function wpb_fp_change_default_post_update_message( $message ){
    $screen = get_current_screen();
    if ( 'wpb_fp_shortcode_gen' == $screen->post_type ){
       $message['post'][1] = $title = __( 'Shortcode updated.',WPB_FP_TEXTDOMAIN );
       $message['post'][4] = $title = __( 'Shortcode updated.',WPB_FP_TEXTDOMAIN );
       $message['post'][6] = $title = __( 'Shortcode published.',WPB_FP_TEXTDOMAIN );
       $message['post'][8] = $title = __( 'Shortcode submitted.',WPB_FP_TEXTDOMAIN );
       $message['post'][10] = $title = __( 'Shortcode draft updated.',WPB_FP_TEXTDOMAIN );
    }
    return $message;
}
add_filter( 'post_updated_messages', 'wpb_fp_change_default_post_update_message' );



/**
 * Getting ready category for the shortcode generator checkbox group
 */


function wpb_fp_tax_for_checkbox_group_meta ( $taxonomy ){
	global $post;
    $terms = get_terms( $taxonomy );
    $output = array();
    foreach ( $terms as $term ) {
    	$output[$term->term_id] = array( 
    		'label' => $term->name,
			'value'	=> $term->term_id,
    	);
    }
    return $output;
}



/**
 * Shortcode Generator meta
 */

add_action( 'init', 'wpb_fp_shortcode_generator_metaboxes' );

function wpb_fp_shortcode_generator_metaboxes(){
	$prefix = 'wpb_fp_';
	$post_type = 'wpb_fp_shortcode_gen';
	$wpb_fp_taxonomy = wpb_fp_get_option( 'wpb_taxonomy_select_', 'wpb_fp_advanced', 'wpb_fp_portfolio_cat' );

	$fields = array(
		array(
			'label' 		=> __( 'Shortcode', WPB_FP_TEXTDOMAIN ),
			'desc'  		=> __( 'Shortcode to use.', WPB_FP_TEXTDOMAIN ),
			'id'    		=> $prefix.'shortcode',
			'type'  		=> 'shortcode',
			'default_value' => 'wpb-portfolio-shortcode'
		),
		array(
			'label'			=> __( 'Number of portfolio', WPB_FP_TEXTDOMAIN ),
			'desc'			=> __( 'Number of portfolio, default -1, it will show all the portfolios', WPB_FP_TEXTDOMAIN ),
			'id'			=> $prefix.'posts',
			'type'			=> 'slider',
			'min'			=> '-1',
			'max'			=> '100',
			'step'			=> '1',
			'default_value' => -1
		),
		array(
			'label'	=> __( 'Need Pagination', WPB_FP_TEXTDOMAIN ),
			'desc'	=> __( 'Default: Off. Check it for enable the pagination', WPB_FP_TEXTDOMAIN ),
			'id'	=> $prefix.'pagination',
			'type'	=> 'checkbox',
		),
		array(
			'label'			=> __( 'Need Filtering', WPB_FP_TEXTDOMAIN ),
			'desc'			=> __( 'Default: On.', WPB_FP_TEXTDOMAIN ),
			'id'			=> $prefix.'filtering',
			'type'			=> 'checkbox',
			'default_value' => '1',
		),
		array(
			'label'	=> __( 'Portfolio Order By', WPB_FP_TEXTDOMAIN ),
			'desc'	=> __( 'Default: date.', WPB_FP_TEXTDOMAIN ),
			'id'	=> $prefix.'orderby',
			'type'	=> 'select',
			'options' => array (
				'date' => array (
					'label' => __( 'Date', WPB_FP_TEXTDOMAIN ),
					'value'	=> 'date'
				),
				'title' => array (
					'label' => __( 'Title', WPB_FP_TEXTDOMAIN ),
					'value'	=> 'title'
				),
				'ID' => array (
					'label' => __( 'ID', WPB_FP_TEXTDOMAIN ),
					'value'	=> 'ID'
				),
				'modified' => array (
					'label' => __( 'Last modified', WPB_FP_TEXTDOMAIN ),
					'value'	=> 'modified'
				),
				'rand' => array (
					'label' => __( 'Random', WPB_FP_TEXTDOMAIN ),
					'value'	=> 'rand'
				),
				'menu_order' => array (
					'label' => __( 'Menu Order', WPB_FP_TEXTDOMAIN ),
					'value'	=> 'menu_order'
				),
			)
		),
		array(
			'label'	=> __( 'Portfolio Order', WPB_FP_TEXTDOMAIN ),
			'desc'	=> __( 'Default: DESC.', WPB_FP_TEXTDOMAIN ),
			'id'	=> $prefix.'order',
			'type'	=> 'select',
			'options' => array (
				'DESC' => array (
					'label' => __( 'Descending', WPB_FP_TEXTDOMAIN ),
					'value'	=> 'DESC'
				),
				'ASC' => array (
					'label' => __( 'Ascending', WPB_FP_TEXTDOMAIN ),
					'value'	=> 'ASC'
				),
			)
		),
		array(
			'label'	=> __( 'Portfolio columns', WPB_FP_TEXTDOMAIN ),
			'desc'	=> __( 'Default 4 columns.', WPB_FP_TEXTDOMAIN ),
			'id'	=> $prefix.'column',
			'type'	=> 'select',
			'options' => array (
				'' => array (
					'label' => __( 'Select portfolio column', WPB_FP_TEXTDOMAIN ),
					'value'	=> ''
				),
				'2' => array (
					'label' => __( '6 Columns', WPB_FP_TEXTDOMAIN ),
					'value'	=> '2'
				),
				'3' => array (
					'label' => __( '4 Columns', WPB_FP_TEXTDOMAIN ),
					'value'	=> '3'
				),
				'4' => array (
					'label' => __( '3 Columns', WPB_FP_TEXTDOMAIN ),
					'value'	=> '4'
				),
				'6' => array (
					'label' => __( '2 Columns', WPB_FP_TEXTDOMAIN ),
					'value'	=> '6'
				),
			)
		),
		array (
			'label'		=> __( 'Portfolio Category [ Include ]', WPB_FP_TEXTDOMAIN ),
			'desc'		=> __( 'This shortcode will show the selected cateories portfolios.', WPB_FP_TEXTDOMAIN ),
			'id'		=> $prefix.'fp_category',
			'type'		=> 'checkbox_group',
			'options' 	=> wpb_fp_tax_for_checkbox_group_meta( $wpb_fp_taxonomy ),
		),
		array (
			'label'		=> __( 'Portfolio Category [ Exclude ]', WPB_FP_TEXTDOMAIN ),
			'desc'		=> __( 'This shortcode will not show the selected cateories portfolios.', WPB_FP_TEXTDOMAIN ),
			'id'		=> $prefix.'exclude_tax',
			'type'		=> 'checkbox_group',
			'options' 	=> wpb_fp_tax_for_checkbox_group_meta( $wpb_fp_taxonomy ),
		),
		array(
			'label'	=> __( 'Image Width', WPB_FP_TEXTDOMAIN ),
			'desc'	=> __( 'Portfolio thumbnail image width. Default 680px', WPB_FP_TEXTDOMAIN ),
			'id'	=> $prefix.'width',
			'type'	=> 'number',
		),
		array(
			'label'	=> __( 'Image Height', WPB_FP_TEXTDOMAIN ),
			'desc'	=> __( 'Portfolio thumbnail image height. Default 680px', WPB_FP_TEXTDOMAIN ),
			'id'	=> $prefix.'height',
			'type'	=> 'number',
		),
		array(
			'label'			=> __( 'Portfolio Post Type', WPB_FP_TEXTDOMAIN ),
			'desc'			=> __( 'Default :', WPB_FP_TEXTDOMAIN ).wpb_fp_get_option( 'wpb_post_type_select_', 'wpb_fp_advanced', 'wpb_fp_portfolio' ),
			'id'			=> $prefix.'post_type',
			'type'			=> 'text',
			'default_value' => wpb_fp_get_option( 'wpb_post_type_select_', 'wpb_fp_advanced', 'wpb_fp_portfolio' ),
		),
		array(
			'label'			=> __( 'Portfolio Taxonomy', WPB_FP_TEXTDOMAIN ),
			'desc'			=> __( 'Default :', WPB_FP_TEXTDOMAIN ).wpb_fp_get_option( 'wpb_taxonomy_select_', 'wpb_fp_advanced', 'wpb_fp_portfolio_cat' ),
			'id'			=> $prefix.'taxonomy',
			'type'			=> 'text',
			'default_value' => wpb_fp_get_option( 'wpb_taxonomy_select_', 'wpb_fp_advanced', 'wpb_fp_portfolio_cat' ),
		),

	);

	/**
	* Instantiate the class with all variables to create a meta box
	* var $id string meta box id
	* var $title string title
	* var $fields array fields
	* var $page string|array post type to add meta box to
	* var $context The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side'). 
	*/

	$portfolio_metabox = new WPB_FP_Custom_Add_Meta_Box( 'portfolio_options', __( 'Shortcode Options', WPB_FP_TEXTDOMAIN ), apply_filters( 'wpb_fp_shortcode_generator_metabox', $fields, $prefix ), $post_type, 'normal' );

}




/**
 * Registering the Generated Shortcode
 */

add_shortcode( 'wpb-portfolio-shortcode','wpb_fp_generated_shortcode_function' );

if( !function_exists('wpb_fp_generated_shortcode_function') ):
	function wpb_fp_generated_shortcode_function( $atts ){
		extract(shortcode_atts(array(
			'id'				=> '',
			'title'				=> ''
		), $atts));

		$filtering = get_post_meta( $id, 'wpb_fp_filtering', true );
		$filtering = $filtering == '1' ? 'filtering="yes"' : 'filtering="no"';

		$pagination = get_post_meta( $id, 'wpb_fp_pagination', true );
		$pagination = $pagination ? 'pagination="on"' : 'pagination="off"';

		$posts = get_post_meta( $id, 'wpb_fp_posts', true );
		$posts = $posts ? 'posts="'.$posts.'"' : '';

		$orderby = get_post_meta( $id, 'wpb_fp_orderby', true );
		$orderby = $orderby ? 'orderby="'.$orderby.'"' : '';

		$order = get_post_meta( $id, 'wpb_fp_order', true );
		$order = $order ? 'order="'.$order.'"' : '';

		$column = get_post_meta( $id, 'wpb_fp_column', true );
		$column = $column ? 'column="'.$column.'"' : '';

		$width = get_post_meta( $id, 'wpb_fp_width', true );
		$width = $width ? 'width="'.$width.'"' : '';

		$height = get_post_meta( $id, 'wpb_fp_height', true );
		$height = $height ? 'height="'.$height.'"' : '';

		$post_type = get_post_meta( $id, 'wpb_fp_post_type', true );
		$post_type = $post_type ? 'post_type="'.$post_type.'"' : '';

		$taxonomy = get_post_meta( $id, 'wpb_fp_taxonomy', true );
		$taxonomy = $taxonomy ? 'taxonomy="'.$taxonomy.'"' : '';

		$fp_category = get_post_meta( $id, 'wpb_fp_fp_category', false );
		$fp_category = !empty($fp_category) ? implode (",", $fp_category[0]) : '';
		$fp_category = $fp_category ? 'fp_category="'.$fp_category.'"' : '';

		$exclude_tax = get_post_meta( $id, 'wpb_fp_exclude_tax', false );
		$exclude_tax = !empty($exclude_tax) ? implode (",", $exclude_tax[0]) : '';
		$exclude_tax = $exclude_tax ? 'exclude_tax="'.$exclude_tax.'"' : '';

	   	ob_start();

		echo do_shortcode( '[wpb-another-portfolio '.$pagination.' '.$filtering.' '.$posts.' '.$orderby.' '.$order.' '.$column.' '.$width.' '.$height.' '.$post_type.' '.$taxonomy.' '.$fp_category.' '.$exclude_tax.' ]' );

		edit_post_link( esc_html__( 'Edit ShortCode', WPB_FP_TEXTDOMAIN ), '', '', $id, 'wpb-fp-shortcode-edit' );

		return ob_get_clean();
	
	}
endif;








/**
 * Adding the media button for adding the portfolio
 */



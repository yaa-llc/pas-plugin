<?php

/*
    WPB Portfolio PRO
    By WPBean
    
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 



if ( ! function_exists('wpb_fp_post_type_select_option_for_meta') ) {

	// Getting all custom post type avaiable for portfolio plugin

	function wpb_fp_post_type_select(){

		$args = array(
		   	'public'   => true,
   			'_builtin' => false
		);

		$rerutn_object = get_post_types( $args );
		$rerutn_object['post'] = 'Post';

		return $rerutn_object;
	}

}


if ( ! function_exists('wpb_fp_taxonomy_select') ) {

	// Getting all custom taxonomy avaiable for portfolio plugin

	function wpb_fp_taxonomy_select(){
		$taxonomy = array();
		$args = array(
			'public' => true,
		);
		$taxonomy_objects = get_taxonomies( $args, 'objects' );
		foreach ($taxonomy_objects as $taxonomy_object) {
			$taxonomy[$taxonomy_object->name] = $taxonomy_object->label;
		}

		return $taxonomy;
	}

}


if ( ! function_exists('wpb_fp_exclude_categories') ) {

	// Exclude selected categiry form portfolio.

	function wpb_fp_exclude_categories(){
		$terms = $category_link = array();
		$wpb_fp_post_type = wpb_fp_get_option( 'wpb_post_type_select_', 'wpb_fp_advanced', 'wpb_fp_portfolio' );
		$taxonomy_objects = get_object_taxonomies( $wpb_fp_post_type, 'objects' );

		if( isset($taxonomy_objects) && !empty($taxonomy_objects) ){
		 	$wpb_fp_taxonomy = wpb_fp_get_option( 'wpb_taxonomy_select_', 'wpb_fp_advanced', 'wpb_fp_portfolio_cat' );
		    $terms = get_terms($wpb_fp_taxonomy);
		    foreach ( $terms as $term ) {
		        $category_link[$term->term_id] = $term->name;
		    }                                         
	      
	    }
	    return $category_link;
	}

}



if ( ! function_exists('wpb_fp_post_type_multicheck_option') ) {

	// Exclude selected categiry form portfolio.

	function wpb_fp_post_type_multicheck_option(){
		$args = array(
		   	'public'   => true,
   			'_builtin' => false
		);
		$output = array();
		$post_types = get_post_types( $args );
		$post_types[] = 'post';

		foreach ($post_types as $post_type) {
			$output[$post_type] = $post_type;
		}

	    return $output;
	}

}




/**
 * Ajax quick view
 */



add_action('wp_ajax_wpb_fp_quickview', 'wpb_fp_quickview');
add_action('wp_ajax_nopriv_wpb_fp_quickview', 'wpb_fp_quickview');

/** The Quickview Ajax Output **/
if( !function_exists('wpb_fp_quickview') ):
	function wpb_fp_quickview() {
	    global $post;
	    $post_id =  $_POST["portfolio"];
	    $post = get_post($post_id);
	    ob_start();

		require_once WPB_FP_URI. 'inc/content-portfolio-lightbox.php';

	    $output = ob_get_contents();
	    ob_end_clean();
	    echo $output;
	    die();
	}
endif;


/**
 * Gallery Image Size
 */

if( !function_exists('wpb_fp_add_image_sizes') ):
	function wpb_fp_add_image_sizes() {
		$width = wpb_fp_get_option( 'wpb_fp_qv_img_width', 'wpb_fp_gallery', 756 );
		$height = wpb_fp_get_option( 'wpb_fp_qv_img_height', 'wpb_fp_gallery', 423 );

		$image_hard_crop = wpb_fp_get_option( 'wpb_fp_gallery_image_hard_crop', 'wpb_fp_gallery', 'on' );
		$x_crop_position = wpb_fp_get_option( 'wpb_fp_gallery_image_x_crop_position', 'wpb_fp_gallery', 'center' );
		$y_crop_position = wpb_fp_get_option( 'wpb_fp_gallery_image_y_crop_position', 'wpb_fp_gallery', 'center' );
		$crop = false;

		if( $image_hard_crop == 'on' ){
			$crop = array( $x_crop_position, $y_crop_position );
		}

	    add_image_size( 'wpb-fp-full', $width, $height, $crop );
	}
endif;
add_action( 'init', 'wpb_fp_add_image_sizes' );


/**
 * Gallery Setting sections
 */

add_filter( 'wpb_fp_settings_sections', 'wpb_fp_gallery_settings_section', 10 );

if( !function_exists('wpb_fp_gallery_settings_section') ):
	function wpb_fp_gallery_settings_section($sections){
		$wpb_fp_gallery_support = wpb_fp_get_option( 'wpb_fp_gallery_support', 'wpb_fp_general' );

		if( $wpb_fp_gallery_support != 'on' ){
			$sections[] = array(
				'id' 	=> 'wpb_fp_gallery',
	            'title' => __( 'Gallery Settings', WPB_FP_TEXTDOMAIN )
			);
		}

		return $sections;
	}
endif;



/**
 * Gallery Setting fields
 */

add_filter( 'wpb_fp_settings_fields', 'wpb_fp_gallery_settings_fields', 10 );

if( !function_exists('wpb_fp_gallery_settings_fields') ):
	function wpb_fp_gallery_settings_fields($settings_fields){
		$wpb_fp_gallery_support = wpb_fp_get_option( 'wpb_fp_gallery_support', 'wpb_fp_general' );

		if( $wpb_fp_gallery_support != 'on' ){
			$settings_fields['wpb_fp_gallery'] = array(
				array(
                    'name'  	=> 'wpb_fp_gallery_feature_image',
                    'label' 	=> __( 'Feature Image in Gallery', WPB_FP_TEXTDOMAIN ),
                    'desc'  	=> __( 'Yes.', WPB_FP_TEXTDOMAIN ),
                    'type'  	=> 'checkbox',
                    'default'   => 'on'
                ),
				array(
                    'name'  => 'wpb_fp_gallery_autoplay',
                    'label' => __( 'Gallery Autoplay ?', WPB_FP_TEXTDOMAIN ),
                    'desc'  => __( 'Yes.', WPB_FP_TEXTDOMAIN ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'  	=> 'wpb_fp_gallery_caption',
                    'label' 	=> __( 'Gallery Image Caption', WPB_FP_TEXTDOMAIN ),
                    'desc'  	=> __( 'Yes.', WPB_FP_TEXTDOMAIN ),
                    'type'  	=> 'checkbox',
                    'default'   => 'on'
                ),
                array(
                    'name'      => 'wpb_fp_gallery_speed',
                    'label'     => __( 'Gallery Speed', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Quickview gallery spped. Default: 600', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'number',
                    'default'   => 600
                ),
                array(
                    'name'  	=> 'wpb_fp_no_resize_the_gallery_image',
                    'label' 	=> __( 'No need to resize the gallery images.', WPB_FP_TEXTDOMAIN ),
                    'desc'  	=> __( 'Load full size images.', WPB_FP_TEXTDOMAIN ),
                    'type'  	=> 'checkbox',
                ),
                array(
                    'name'      => 'wpb_fp_qv_img_width',
                    'label'     => __( 'Image Width', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Quickview gallery image width. Default 756. Use <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerate Thumbnails</a> plugin & regenerate all thumbnails after changing the size and other image settings.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'number',
                    'default'   => 756
                ),
                array(
                    'name'      => 'wpb_fp_qv_img_height',
                    'label'     => __( 'Image height', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Quickview gallery image height. Default 423', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'number',
                    'default'   => 423
                ),
                array(
                    'name'  	=> 'wpb_fp_gallery_image_hard_crop',
                    'label' 	=> __( 'Hard crop gallery images.', WPB_FP_TEXTDOMAIN ),
                    'desc'  	=> __( 'Yes.', WPB_FP_TEXTDOMAIN ),
                    'type'  	=> 'checkbox',
                    'default'   => 'on'
                ),
                array(
                    'name'      => 'wpb_fp_gallery_image_x_crop_position',
                    'label'     => __( 'X crop position', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'When setting a crop position, the first value in the array is the x axis crop position, the second is the y axis crop position. <a href="https://developer.wordpress.org/reference/functions/add_image_size/#crop-mode" target="_blank">Details</a>', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'select',
                    'default'   => 'center',
                    'options'   => array(
                        'center'     => __( 'Center', WPB_FP_TEXTDOMAIN ),
                        'left'    	 => __( 'Left', WPB_FP_TEXTDOMAIN ),
                        'right'      => __( 'Right', WPB_FP_TEXTDOMAIN ),
                    )
                ),
                array(
                    'name'      => 'wpb_fp_gallery_image_y_crop_position',
                    'label'     => __( 'Y crop position', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'When setting a crop position, the first value in the array is the x axis crop position, the second is the y axis crop position. <a href="https://developer.wordpress.org/reference/functions/add_image_size/#crop-mode" target="_blank">Details</a>', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'select',
                    'default'   => 'center',
                    'options'   => array(
                        'center'     => __( 'Center', WPB_FP_TEXTDOMAIN ),
                        'top'    	 => __( 'Top', WPB_FP_TEXTDOMAIN ),
                        'bottom'     => __( 'Bottom', WPB_FP_TEXTDOMAIN ),
                    )
                ),
			);
		}

		return $settings_fields;
	}
endif;



/**
 * Portfolio Pagination 
 */

if( !function_exists('wpb_fp_pagination') ):
	function wpb_fp_pagination($numpages = '', $pagerange = '', $paged='') {

		if (empty($pagerange)) {
			$pagerange = 2;
		}

		/**
		* This first part of our function is a fallback
		* for custom pagination inside a regular loop that
		* uses the global $paged and global $wp_query variables.
		* 
		* It's good because we can now override default pagination
		* in our theme, and use this function in default quries
		* and custom queries.
		*/
		global $paged;
		if (empty($paged)) {
			$paged = 1;
		}
		if ($numpages == '') {
			global $wp_query;
			$numpages = $wp_query->max_num_pages;
			if(!$numpages) {
				$numpages = 1;
			}
		}
		$big = 999999999; // need an unlikely integer

		$output = '';

		/** 
		* We construct the pagination arguments to enter into our paginate_links
		* function. 
		*/
		$pagination_args = array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'          => '?paged=%#%',
			'total'           => $numpages,
			'current'         => max( 1, $paged ),
			'show_all'        => False,
			'end_size'        => 1,
			'mid_size'        => $pagerange,
			'prev_next'       => True,
			'prev_text'       => __('&laquo;'),
			'next_text'       => __('&raquo;'),
			'type'            => 'array',
			'add_args'        => false,
			'add_fragment'    => ''
		);

		$pages = paginate_links($pagination_args);

		if( is_array( $pages ) ) {
			$output .= "<ul class='wpb-fp-pagination pagination'>";
			$output .=  sprintf( '<li class="page-numbers page-num">'. __( 'page %s of %s', WPB_FP_TEXTDOMAIN ) .'<li>',  esc_html( $paged ), esc_html( $numpages ) );
			foreach ( $pages as $page ) {
		        $output .=  "<li>$page</li>";
		    }
			$output .= "</ul>";
			return $output;
		}

	}
endif;



/**
 * Visual Composer Element
 */

function wpb_fp_get_shortcodes_list(){
	$output = array( __( 'Select a shortcode', WPB_FP_TEXTDOMAIN ) => '' );
	$args = array(
		'posts_per_page'   => -1,
		'post_type'        => 'wpb_fp_shortcode_gen',
		'post_status'      => 'publish',
	);

	$posts = get_posts( $args );

	foreach ( $posts as $post ) {
		$output[$post->post_title] = $post->ID;
	}

	return $output;
}



if ( ! class_exists( 'wpb_fp_vc_elements_class' ) ) {

	/**
	 * Logo slider VC element Class
	 *
	 * @since	1.0
	 */
	class wpb_fp_vc_elements_class {


		public $text_domain = WPB_FP_TEXTDOMAIN; // textdomain for the plugin


		/**
		 * Constructor, checks for Visual Composer and defines hooks
		 *
		 * @since	1.0
		 */
		function __construct() {
            add_action( 'after_setup_theme', array( $this, 'wpb_fp_init' ), 1 );
		}

        public function wpb_fp_init() {
            if ( ! defined( 'WPB_VC_VERSION' ) ) {
                return;
            }
            if ( version_compare( WPB_VC_VERSION, '4.2', '<' ) ) {
        		add_action( 'after_setup_theme', array( $this, 'wpb_fp_vc_element' ) );
            } else {
        		add_action( 'vc_before_init', array( $this, 'wpb_fp_vc_element' ) );
            }
        }



		public function wpb_fp_vc_element() {
			// Check if Visual Composer is installed
			if ( ! defined( 'WPB_VC_VERSION' ) || ! function_exists( 'vc_add_param' ) ) {
				return;
			}


			vc_map( array(				
				'name' 			=> __( 'WPB Portfolio', $this->text_domain ),
				'base' 			=> 'wpb-portfolio-shortcode',
				'icon' 			=> 'wpb_fp_icon',
				'wrapper_class' => 'clearfix',
				'description' 	=> __( 'WPB filterable portfolio.', $this->text_domain ),
			    'params' 		=> array(
				
					array(
						'type' 			=> 'dropdown',
						'heading' 		=> __( 'Shortcode', $this->text_domain ),
						'param_name' 	=> 'id',
						'value'			=> wpb_fp_get_shortcodes_list(),
						'description' 	=> __( 'Choose a shortcode. Go to shortcode generator for adding a new shortcode.', $this->text_domain ),
						'admin_label' 	=> true,
					)
					
			    ),
			) );
		}

	}

	new wpb_fp_vc_elements_class();
}



/**
 * Add Portfolio Images
 */

add_action( 'init', 'wpb_fp_add_new_image_size' );
function wpb_fp_add_new_image_size() {
	$width = wpb_fp_get_option( 'wpb_fp_image_width_', 'wpb_fp_advanced', 680 );
	$height = wpb_fp_get_option( 'wpb_fp_image_height_', 'wpb_fp_advanced', 680 );
    add_image_size( 'wpb_portfolio_thumbnail', $width, $height, true );
}


/**
 * Adding portfolio video and gallery images in portfolio details page
 */

add_filter( 'the_content', 'wpb_fp_show_gallery_images_video_in_portfolio_page' );

if( !function_exists('wpb_fp_show_gallery_images_video_in_portfolio_page') ){
	function wpb_fp_show_gallery_images_video_in_portfolio_page( $content ){
		$wpb_fp_post_type = wpb_fp_get_option( 'wpb_post_type_select_', 'wpb_fp_advanced', 'wpb_fp_portfolio' );
		$gallery_images_in_portfolio_page = wpb_fp_get_option( 'wpb_fp_gallery_images_in_portfolio_page', 'wpb_fp_general', 'on' );
		$video_in_portfolio_page = wpb_fp_get_option( 'wpb_fp_video_in_portfolio_page', 'wpb_fp_general', 'on' );

		$video_iframe = get_post_meta( get_the_id(), 'wpb_fp_quickview_video_iframe', true );

		if( is_singular( $wpb_fp_post_type ) && $gallery_images_in_portfolio_page == 'on' ) {
			wpb_fp_get_scripts();
			$content =  wpb_fp_quickview_galllery( '', get_the_id() ) . $content;
		}

		if( is_singular( $wpb_fp_post_type ) && $video_in_portfolio_page == 'on' ) {
			$content = $video_iframe . $content;
		}

		return $content;
	}
}
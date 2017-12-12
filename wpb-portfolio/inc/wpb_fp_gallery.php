<?php

/*
    WPB Portfolio PRO
    By WPBean
    
    Gallery support comes with v 1.07
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/**
 * Gallery Scripts
 */

function wpb_fp_galllery_scripts() {
	wp_register_style('wpb-fp-lightslider', plugins_url('../assets/css/lightslider.min.css', __FILE__), '', '1.0');
	wp_register_script('wpb-fp-lightslider-js', plugins_url('../assets/js/lightslider.min.js', __FILE__), array('jquery'), '1.0', false);

	if( is_singular( 'wpb_fp_portfolio' ) ){
		wp_enqueue_style('wpb-fp-lightslider');
		wp_enqueue_script('wpb-fp-lightslider-js');
	}
}
add_action( 'wp_enqueue_scripts', 'wpb_fp_galllery_scripts' ); 


/**
 * Adding the Gallery Meta Box
 */


add_filter( 'wpb_fp_metabox', 'wpb_fp_portfolio_gallery_meta', 10, 2 );
if( !function_exists('wpb_fp_portfolio_gallery_meta') ){

	function wpb_fp_portfolio_gallery_meta( $fields, $prefix ){
		$fields[] = array(
			'label' => __( 'Image Gallery', WPB_FP_TEXTDOMAIN ),
			'id'    => $prefix.'gallery',
			'type'  => 'gallery',
			'desc'  => __( 'Choose images for gallery here.', WPB_FP_TEXTDOMAIN ),
		);

		return $fields;
	}
}



/**
 * Display the gallery images in quick view 
 */

add_filter( 'wpb_fp_quickview_feature_image', 'wpb_fp_quickview_galllery', 10, 2 );
if( !function_exists('wpb_fp_quickview_galllery') ){

	function wpb_fp_quickview_galllery( $value, $id ){
		
		$wpb_fp_no_resize_the_gallery_image = wpb_fp_get_option( 'wpb_fp_no_resize_the_gallery_image', 'wpb_fp_gallery', '' );
		$gallery_image_size = ( $wpb_fp_no_resize_the_gallery_image == 'on' ? 'full' : 'wpb-fp-full' );

		$images = get_post_meta( $id, 'wpb_fp_gallery', true );
		$feature_image = get_the_post_thumbnail( $id, $gallery_image_size );
		$feature_image_src = wp_get_attachment_image_src( $id, $gallery_image_size );
		$wpb_fp_gallery_caption = wpb_fp_get_option( 'wpb_fp_gallery_caption', 'wpb_fp_gallery', 'on' );
		$gallery_feature_image = wpb_fp_get_option( 'wpb_fp_gallery_feature_image', 'wpb_fp_gallery', 'on' );

		if( ! empty( $images ) ) {
			$caption = get_post(get_post_thumbnail_id())->post_excerpt;
			$caption = ( $caption ? '<div class="wpb_fp_caption"><p>'.$caption.'</p></div>' : '' );
			$value = '';
			$images = explode( ',', $images );
			$value .= '<ul id="wpb_fp_gallery_'.$id.'">';
			if( $feature_image && $feature_image != '' && $gallery_feature_image == 'on' ){

				$value .= '<li data-thumb="'. $feature_image_src[0] .'">'.$feature_image.( $wpb_fp_gallery_caption == 'on' ? $caption : '' ).'</li>';
			}
			foreach ( $images as $image ) {
				$thumb = wp_get_attachment_image_src( $image, 'thumbnail' );
				$large = wp_get_attachment_image_src( $image, $gallery_image_size );
				$alt = get_post_meta($image, '_wp_attachment_image_alt', true);
				$gallery_caption = get_post_field('post_excerpt', $image);
				$gallery_caption = ( $gallery_caption ? '<div class="wpb_fp_caption"><p>'.$gallery_caption.'</p></div>' : '' );
				$value .= '<li data-thumb="'. $thumb[0] .'"><img src="'. $large[0] .'" alt="'. $alt .'" />'.( $wpb_fp_gallery_caption == 'on' ? $gallery_caption : '' ).'</li>';
			}

			$rtl = ( is_rtl() ? 'rtl:true,' : '' );
			$wpb_fp_gallery_autoplay = wpb_fp_get_option( 'wpb_fp_gallery_autoplay', 'wpb_fp_gallery', 'off' );
			$auto = ( $wpb_fp_gallery_autoplay === 'on' ? 'true' : 'false' );
			$wpb_fp_gallery_speed = wpb_fp_get_option( 'wpb_fp_gallery_speed', 'wpb_fp_gallery', 600 );

			$value .= '</ul>';
			$value .= '<script type="text/javascript">';
			$value .= "jQuery('#wpb_fp_gallery_{$id}').lightSlider({
						    gallery: true,
						    auto: $auto,
						    speed: $wpb_fp_gallery_speed,
						    item: 1,
						    loop: true,
						    slideMargin: 0,
						    thumbItem: 9,
						    $rtl
						});
					";
			$value .= '</script>';

	    }

		return $value;
	}
}
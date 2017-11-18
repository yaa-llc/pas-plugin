<?php

/*
	Advance Portfolio Grid
	By WPBean
	
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


/* ==========================================================================
   Shortcode For this plugin
   ========================================================================== */


function wpb_fp_shortcode_funcation( $atts ){
	extract(shortcode_atts(array(
		'orderby'				=> 'none', // portfolio orderby
		'order'					=> '', // portfolio order
	), $atts));
   
	$portfolio_id = rand( 10,1000 );

	global $post;
	$wpb_fp_number_of_post = wpb_fp_get_option( 'wpb_fp_number_of_post_', 'wpb_fp_general', -1 );
	$wpb_post_type_select = wpb_fp_get_option( 'wpb_post_type_select_', 'wpb_fp_advanced', 'wpb_fp_portfolio' );
	$args 	= array(
		'post_type' 		=> $wpb_post_type_select,
		'posts_per_page'	=> $wpb_fp_number_of_post,
		
	);

	// Exclude selected categiry form portfolio.
	$wpb_fp_cat_excludes = wpb_fp_get_option( 'wpb_fp_cat_exclude_', 'wpb_fp_advanced', 'show' );
	$wpb_fp_taxonomy = wpb_fp_get_option( 'wpb_taxonomy_select_', 'wpb_fp_advanced', 'show' );
	if( isset($wpb_fp_cat_excludes) && is_array($wpb_fp_cat_excludes) ){
		foreach ($wpb_fp_cat_excludes as $wpb_fp_cat_exclude_key => $wpb_fp_cat_exclude_value) {
			$wpb_fp_cats_ex[] = $wpb_fp_cat_exclude_key;
		}

		$args['tax_query'][] = array(
			'taxonomy' 	=> $wpb_fp_taxonomy,
	        'field'    	=> 'id',
			'terms'    	=> $wpb_fp_cats_ex,
	        'operator' 	=> 'NOT IN' 
		);
	}

	$loop = new WP_Query( $args );
	if ( $loop->have_posts() ) {
		$output = '<div class="wpb_portfolio_area">';
		$output .= '<div class="wpb_portfolio wpb_fp_row wpb_fp_grid" id="wpb_portfolio_'.$portfolio_id.'">';

		while ( $loop->have_posts() ) : $loop->the_post();
			global $post;
			$thumb = get_post_thumbnail_id();
			$img_url = wp_get_attachment_url( $thumb,'full' );
			$wpb_fp_image_width = wpb_fp_get_option( 'wpb_fp_image_width_', 'wpb_fp_advanced', 275 );
			$wpb_fp_image_height = wpb_fp_get_option( 'wpb_fp_image_height_', 'wpb_fp_advanced', 135 );
			$image_thumb = aq_resize( $img_url, $wpb_fp_image_width, $wpb_fp_image_height, true, true, true ); //resize & crop the image
			$thumbnail_mata = get_post_meta($thumb,'_wp_attachment_image_alt',true);
			$wpb_fp_column = wpb_fp_get_option( 'wpb_fp_column_', 'wpb_fp_general', 4 );
			$wpb_fp_show_overlay = wpb_fp_get_option( 'wpb_fp_show_overlay_', 'wpb_fp_advanced', 'show' );
			$wpb_fp_show_links = wpb_fp_get_option( 'wpb_fp_show_links_', 'wpb_fp_advanced', 'show' );
			$wpb_fp_portfolio_ex_link = get_post_meta( $post->ID, 'wpb_fp_portfolio_ex_link', true );
			$wpb_fp_popup_effect = wpb_fp_get_option( 'wpb_fp_popup_effect_', 'wpb_fp_style', 'mfp-zoom-in' );
			$wpb_fp_hover_effect = wpb_fp_get_option( 'wpb_fp_hover_effect_', 'wpb_fp_style', 'effect-roxy' );
			$portfolio_title = get_the_title();
			$portfolio_title = (strlen($portfolio_title) > 18) ? substr($portfolio_title,0,16).'...' : $portfolio_title;

			$output .= '<div class="wpb_fp_col-md-'.$wpb_fp_column.' wpb_fp_col-sm-6 wpb_fp_col-xs-12 wpb_portfolio_post">';
			$output .= '<figure class="'. $wpb_fp_hover_effect .'">';
			$output .= '<img src="'.$image_thumb.'" alt="img12"/>';
			if( isset($wpb_fp_show_overlay) && $wpb_fp_show_overlay == 'show' ):
				$output .= '<figcaption>';
				$output .= '<div>';
				$output .= '<h2>'. $portfolio_title .'</h2>';
				if( isset($wpb_fp_show_links) && $wpb_fp_show_links == 'show' ):
					$output .= '<p class="wpb_fp_icons">';
					$output .= '<a class="wpb_fp_preview open-popup-link" href="#wpb_fp_quick_view_'.get_the_id().'" data-effect="'.$wpb_fp_popup_effect.'"><i class="fa fa-eye"></i></a>';
					$output .= '<a class="wpb_fp_link" href="'.get_permalink().'"><i class="fa fa-link"></i></a>';
					$output .= '</p>';
				endif;
				$output .= '</div>';
				$output .= '</figcaption>';	
			endif;
			$output .= '</figure>';
			$output .= '</div>';

			// Quick view
			$output .= '<div id="wpb_fp_quick_view_'.get_the_id().'" class="white-popup mfp-hide mfp-with-anim wpb_fp_quick_view">';
			$output .= '<div class="wpb_fp_row">';
			$output .= '<div class="wpb_fp_quick_view_img wpb_fp_col-md-6 wpb_fp_col-sm-12">';
			$output .= '<img src="'.$img_url.'" alt="'.$thumbnail_mata.'">';
			$output .= '</div>';
			$output .= '<div class="wpb_fp_quick_view_content wpb_fp_col-md-6 wpb_fp_col-sm-12">';
			$output .= '<h2>'. get_the_title() .'</h2>';
			$output .= wpautop( apply_filters( 'the_content', get_the_content() ) );
			if( isset($wpb_fp_portfolio_ex_link) && !empty($wpb_fp_portfolio_ex_link) ){
				$output .= '<a class="wpb_fp_btn" href="'.$wpb_fp_portfolio_ex_link.'">'.wpb_fp_get_option( 'wpb_fp_view_portfolio_btn_text_', 'wpb_fp_advanced', 'View Portfolio' ).'</a>';
			}
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
			// quick view end
			
		endwhile;

		$output .= '</div><!-- wpb_portfolio -->';
		$output .= '</div><!-- wpb_portfolio_area -->';
		do_action('wpb_fp_after_portfolio');
	} else {
		$output = __( 'No portfolio found', 'wpb_fp' );
	}
	wp_reset_postdata();
	
	wp_reset_query();
	
	return $output;
}
add_shortcode( 'wpb-portfolio','wpb_fp_shortcode_funcation' );	





<?php

/*
	WPB Filterable Portfolio
	By WPBean
	
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


/* ==========================================================================
   Portfolio Post Category Name in Div class function
   ========================================================================== */

if( !function_exists('wpb_fp_portfolio_categories') ):
	function wpb_fp_portfolio_categories( $taxonomy, $id ){
	    global $post;
	    $category = '';
	    $terms = get_the_terms( $post->ID, $taxonomy );
	                                                   
	    if ( $terms && ! is_wp_error( $terms ) ) :
	     
	            $category_link = array();
	     
	            foreach ( $terms as $term ) {
	                $category_link[] = 'wpbfp_cat_'.$id.'_'.$term->term_id;;
	            }

	            $category = implode(" ", $category_link);
	           
	    endif;

	    return $category;      
	}
endif;


/* ==========================================================================
   Shortcode For this plugin
   ========================================================================== */


add_shortcode( 'wpb-portfolio','wpb_fp_shortcode_funcation' );	

if( !function_exists( 'wpb_fp_shortcode_funcation' ) ):
	function wpb_fp_shortcode_funcation( $atts ){
		extract(shortcode_atts(array(
			'orderby'				=> 'none', // portfolio orderby
			'order'					=> '', // portfolio order
		), $atts));
	   
		ob_start();

		echo do_shortcode( '[wpb-another-portfolio orderby="'.$orderby.'" order="'.$order.'"]' );

		return ob_get_clean();
	}
endif;



/* ==========================================================================
   Another Portfolio
   Added since V 1.06
   ========================================================================== */

add_shortcode( 'wpb-another-portfolio','wpb_fp_another_portfolio_shortcode_funcation' );

if( !function_exists('wpb_fp_another_portfolio_shortcode_funcation') ):
	function wpb_fp_another_portfolio_shortcode_funcation( $atts ){

		extract(shortcode_atts(array(
			'orderby'				=> 'date', // portfolio orderby
			'order'					=> 'DESC', // portfolio order
			'fp_category'			=> '', // comma separated cat id's
			'exclude_tax'			=> '', // comma separated cat id's
			'posts'					=> -1, // Number of post
			'pagination'			=> 'off',
			'filtering'				=> 'yes',
			'column' 				=> wpb_fp_get_option( 'wpb_fp_column_', 'wpb_fp_general', 4 ),
			'width' 				=> wpb_fp_get_option( 'wpb_fp_image_width_', 'wpb_fp_advanced', 680 ),
			'height' 				=> wpb_fp_get_option( 'wpb_fp_image_height_', 'wpb_fp_advanced', 680 ),
			'post_type' 			=> wpb_fp_get_option( 'wpb_post_type_select_', 'wpb_fp_advanced', 'wpb_fp_portfolio' ),
			'taxonomy' 				=> wpb_fp_get_option( 'wpb_taxonomy_select_', 'wpb_fp_advanced', 'wpb_fp_portfolio_cat' ),
		), $atts));

	    global $post;
		$rand_id = rand( 10,1000 );

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$args = array(
			'post_type' 		=> $post_type,
			'posts_per_page'	=> $posts,
			'orderby' 			=> $orderby,
			'order' 			=> $order,
			'paged' 			=> $paged
		);

		// Exclude selected categories form portfolio.
		
		if( $exclude_tax && $exclude_tax != '' ){
			$exclude_tax = explode(',', $exclude_tax);
			$args['tax_query'][] = array(
				'taxonomy' 	=> $taxonomy,
		        'field'    	=> 'id',
				'terms'    	=> $exclude_tax,
		        'operator' 	=> 'NOT IN' 
			);
		}

		// only selected categories
		if( $fp_category && $fp_category != '' ){
			$fp_category = explode(',', $fp_category);
			$args['tax_query'][] = array(
				'taxonomy' 	=> $taxonomy,
		        'field'    	=> 'id',
				'terms'    	=> $fp_category,
		        'operator' 	=> 'IN' 
			);
		}

		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			$output = '<div class="wpb_portfolio_area wpb_category_portfolio" data-mix="#wpb_portfolio_'.$rand_id.'">';
			$wpb_fp_filtering = wpb_fp_get_option( 'wpb_fp_filtering', 'wpb_fp_advanced', 'enable' );

			if( $wpb_fp_filtering == 'enable' ):
				$terms_args = array();
				if( isset($exclude_tax) && is_array($exclude_tax) ){
					$terms_args = array( 'exclude' => $exclude_tax );
				}
				if( isset($fp_category) && is_array($fp_category) ){
					$terms_args = array( 'include' => $fp_category );
				}

				$terms = get_terms( $taxonomy, apply_filters( 'wpb_fp_filter_terms_args', $terms_args ) );
				$count = count($terms);
				if ( $count > 0 && $filtering == 'yes'){
					$wpb_fp_filter_position = wpb_fp_get_option( 'wpb_fp_filter_position_', 'wpb_fp_general', 'center' );
					$wpb_fp_filter_style = wpb_fp_get_option( 'wpb_fp_filter_style_', 'wpb_fp_style', 'default' );
					$wpb_fp_show_counting = wpb_fp_get_option( 'wpb_fp_show_counting_', 'wpb_fp_general', 'show' );

					if( isset($wpb_fp_filter_style) && $wpb_fp_filter_style == 'Select' ){
						$output .= '<div id="wpb_fp_filter_select"><a href="#" id="wpb-fp-sort-portfolio"><span>'.wpb_fp_get_option( 'wpb_fp_all_btn_text', 'wpb_fp_general', __( 'All', WPB_FP_TEXTDOMAIN ) ).'</span> <i class="fa fa-angle-down"></i></a>';
					}
					
			        $output .= '<ul class="wpb-fp-filter wpb_fp_text-'. $wpb_fp_filter_position .' wpb_fp_filter_'.$wpb_fp_filter_style.'">';
			        
			        $output .= '<li class="filter" data-filter="all">'.wpb_fp_get_option( 'wpb_fp_all_btn_text', 'wpb_fp_general', __( 'All', WPB_FP_TEXTDOMAIN ) ).'</li>';

					foreach ( $terms as $term ) {

			            $termname = 'wpbfp_cat_'.$rand_id.'_'.$term->term_id;

						if( isset($wpb_fp_show_counting) && $wpb_fp_show_counting == 'show' ){   
							$output .= '<li class="filter" data-filter="' . '.' . $termname . '" title="' . $term->count . '">' . $term->name . '</li>';
						}else{
							$output .= '<li class="filter" data-filter="' . '.' . $termname . '">' . $term->name . '</li>';
						}
					}
						$output .= '</ul>';
				
						if( isset($wpb_fp_filter_style) && $wpb_fp_filter_style == 'Select' ){
							$output .= '</div><div class="wpb_fp_clear"></div>';
						}
				}
			endif;

			$output .= '<div class="wpb_portfolio wpb_fp_row wpb_fp_grid" id="wpb_portfolio_'.$rand_id.'">';

			while ( $loop->have_posts() ) : $loop->the_post();
				global $post;

				$thumb = get_post_thumbnail_id();
				$img_url = wp_get_attachment_url( $thumb,'full' );
				$image_thumb = wpb_fp_resize( $img_url, $width, $height, true, true, true ); //resize & crop the image
				$alt = get_post_meta($thumb,'_wp_attachment_image_alt',true);
				$wpb_fp_show_overlay = wpb_fp_get_option( 'wpb_fp_show_overlay_', 'wpb_fp_advanced', 'show' );
				$wpb_fp_quickview_icon = wpb_fp_get_option( 'wpb_fp_quickview_icon', 'wpb_fp_advanced', 'show' );
				$wpb_fp_single_portfolio_link = wpb_fp_get_option( 'wpb_fp_single_portfolio_link', 'wpb_fp_advanced', 'show' );
				$wpb_fp_portfolio_ex_link = get_post_meta( $post->ID, 'wpb_fp_portfolio_ex_link', true );
				$wpb_fp_popup_effect = wpb_fp_get_option( 'wpb_fp_popup_effect_', 'wpb_fp_style', 'mfp-zoom-in' );
				$wpb_fp_hover_effect = wpb_fp_get_option( 'wpb_fp_hover_effect_', 'wpb_fp_style', 'effect-roxy' );
				$wpb_fp_title_character_limit = wpb_fp_get_option( 'wpb_fp_title_character_limit_', 'wpb_fp_general', 'on' );
				$wpb_fp_number_of_title_character = wpb_fp_get_option( 'wpb_fp_number_of_title_character', 'wpb_fp_general', 16 );
				$wpb_fp_after_title = wpb_fp_get_option( 'wpb_fp_after_title', 'wpb_fp_general', '...' );

				if( $wpb_fp_title_character_limit === 'on' ){
					$portfolio_title = get_the_title();
					$portfolio_title = ( strlen($portfolio_title) > $wpb_fp_number_of_title_character + 2 ) ? substr($portfolio_title,0,$wpb_fp_number_of_title_character ).$wpb_fp_after_title : $portfolio_title;
				}else{
					$portfolio_title = get_the_title();
				}

				$portfolio_permalink = get_post_meta( $post->ID, 'wpb_fp_portfolio_ex_link', true );
				$portfolio_link_target = '';
				if( $portfolio_permalink && $portfolio_permalink != '' ){
					$portfolio_permalink = get_post_meta( $post->ID, 'wpb_fp_portfolio_ex_link', true );
					$portfolio_link_target = 'target="_blank"';
				}elseif( isset($wpb_fp_single_portfolio_link) && $wpb_fp_single_portfolio_link == 'show' ){
					$portfolio_permalink = get_permalink();
				}else {
					$portfolio_permalink = '';
				}

				$content_type = get_post_meta( $post->ID, 'wpb_fp_content_type', true );
				$video_iframe = get_post_meta( $post->ID, 'wpb_fp_video_iframe', true );

				$wpb_fp_image_hard_crop = wpb_fp_get_option( 'wpb_fp_image_hard_crop_', 'wpb_fp_advanced', 'yes' );
				if( $wpb_fp_image_hard_crop == 'yes' ){
					$feature_image = '<img src="'.$image_thumb.'" alt="'.$alt.'"/>';
				}else{
					$feature_image = get_the_post_thumbnail( $post->ID, 'wpb_portfolio_thumbnail' );
				}
				

				if( $content_type && $content_type == 'video'){
					$grid_content = $video_iframe;
				}else{
					$grid_content = $feature_image;
				}

				$specific_overlay = get_post_meta( $post->ID, 'wpb_fp_disable_overlay', true );
				$wpb_fp_link_full_grid = wpb_fp_get_option( 'wpb_fp_link_full_grid_', 'wpb_fp_advanced', '' );
				$wpb_fp_link_full_grid_type = wpb_fp_get_option( 'wpb_fp_link_full_grid_type_', 'wpb_fp_advanced', 'details_page' );

				// Link Image if overlay is disabled
				if( $wpb_fp_show_overlay == 'hide' && $wpb_fp_link_full_grid == 'yes' ):

					if( $wpb_fp_link_full_grid_type == 'details_page' ){

						$grid_content = '<a class="wpb_fp_link_main_image" '.$portfolio_link_target.' href="'.$portfolio_permalink.'">'.$grid_content.'</a>';

					} elseif( $wpb_fp_link_full_grid_type == 'quickview_popup' ){

						$grid_content = '<a data-post-id="' . $post->ID . '" class="wpb_fp_preview open-popup-link" href="#" data-effect="'.$wpb_fp_popup_effect.'">'.$grid_content.'</a>';
						
					}

				endif;

				// Link Image if overlay is eabled

				$link_full_grid = '';

				if( $wpb_fp_show_overlay == 'show' && $wpb_fp_link_full_grid == 'yes' ){

					if( $wpb_fp_link_full_grid_type == 'details_page' ){
						$link_full_grid = '<a class="link_full_grid" '.$portfolio_link_target.' href="'.$portfolio_permalink.'"></a>';
					} elseif( $wpb_fp_link_full_grid_type == 'quickview_popup' ){
						$link_full_grid = '<a data-post-id="' . $post->ID . '" class="link_full_grid wpb_fp_preview open-popup-link" href="#" data-effect="'.$wpb_fp_popup_effect.'"></a>';
					}

				}


				$output .= '<div class="'. apply_filters( 'wpb_fp_portfolio_column_class', 'wpb_fp_col-md-'.$column.' wpb_fp_col-sm-6 wpb_fp_col-xs-12' ) .' mix '.wpb_fp_portfolio_categories( $taxonomy, $rand_id ).'">';
				$output .= '<figure class="'. $wpb_fp_hover_effect .'">';
				$output .= $grid_content;
				if( isset($wpb_fp_show_overlay) && $wpb_fp_show_overlay == 'show' && $specific_overlay === '' ):
					$output .= '<figcaption>';
					$output .= $link_full_grid;
					$output .= '<div>';
					$output .= '<h2>'. $portfolio_title .'</h2>';
					if( isset($portfolio_permalink) || $wpb_fp_quickview_icon == 'show' ):
						$output .= '<p class="wpb_fp_icons">';

						if( isset($wpb_fp_quickview_icon) && $wpb_fp_quickview_icon == 'show' ):
							$output .= '<a data-post-id="' . $post->ID . '" class="wpb_fp_preview open-popup-link" href="#" data-effect="'.$wpb_fp_popup_effect.'">'. apply_filters( 'wpb_fp_quickview_icon', '<i class="fa fa-eye"></i>' ) .'</a>';
						endif;
						if( isset($portfolio_permalink) && $portfolio_permalink != '' && $wpb_fp_single_portfolio_link == 'show' ):
							$output .= '<a class="wpb_fp_link" '.$portfolio_link_target.' href="'.$portfolio_permalink.'">'. apply_filters( 'wpb_fp_link_icon', '<i class="fa fa-link"></i>' ) .'</a>';
						endif;

						$output .= '</p>';
					endif;
					$output .= '</div>';
					$output .= '</figcaption>';	
				endif;
				$output .= '</figure>';
				$output .= '</div>';
				
			endwhile;

			$output .= '</div><!-- wpb_portfolio -->';
			$output .= '</div><!-- wpb_portfolio_area -->';

			if ( function_exists('wpb_fp_pagination') && $pagination == 'on' ) {
				$output .=	wpb_fp_pagination( $loop->max_num_pages, "", $paged );
			}

			$output .= do_action('wpb_fp_after_portfolio');

			wp_reset_postdata();

		} else {
			$output = __( 'No portfolio found.', WPB_FP_TEXTDOMAIN );
		}

		wpb_fp_get_scripts();		

		return $output;

	}
endif;
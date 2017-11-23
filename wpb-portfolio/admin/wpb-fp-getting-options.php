<?php

/*
	WPB Filterable Portfolio
	By WPBean
	
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


/* ==========================================================================
   Changeable Styles
   ========================================================================== */

function wob_fp_changeable_styles(){
	$wpb_fp_primary_color = wpb_fp_get_option( 'wpb_fp_primary_color_', 'wpb_fp_style', '#DF6537' );
?>
	<style type="text/css">
	/* Color */
	.wpb-fp-filter li:hover, 
	.wpb_portfolio .wpb_fp_icons .wpb_fp_preview i,
	.wpb_fp_quick_view_content .wpb_fp_btn:hover{
		color: <?php echo $wpb_fp_primary_color; ?>;
	}
	/* Border color */
	.tooltipster-punk, 
	.wpb_fp_filter_default li:hover,
	.wpb_fp_quick_view_content .wpb_fp_btn:hover,
	.wpb_fp_quick_view_content .wpb_fp_btn {
		border-color: <?php echo $wpb_fp_primary_color; ?>;
	}
	/* Background color */
	.wpb_portfolio .wpb_fp_icons .wpb_fp_link i,
	.wpb_fp_btn,
	.wpb_fp_filter_capsule li.active,
	#wpb_fp_filter_select,
	#wpb_fp_filter_select #wpb-fp-sort-portfolio,
	#wpb_fp_filter_select li,
	.wpb-fp-pagination a.page-numbers:hover {
		background: <?php echo $wpb_fp_primary_color; ?>;
	}
	/* Title font size */
	.wpb_fp_grid figure h2 {
		font-size: <?php echo wpb_fp_get_option( 'wpb_fp_title_font_size_', 'wpb_fp_style', 20 ); ?>px;
	}

	</style>
<?php
}
add_action( 'wp_head','wob_fp_changeable_styles' );

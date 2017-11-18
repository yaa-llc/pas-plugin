<?php

/*
	Advance Portfolio Grid
	By WPBean
	
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 



/* ==========================================================================
   Magnific Popup Trigger
   ========================================================================== */


function wpb_fp_magnific_popup_trigger(){
?>

	<script type="text/javascript">
		jQuery(function(){

			jQuery('.wpb_portfolio').magnificPopup({
				type:'inline',
				midClick: true,
				gallery:{
					enabled:true
				},
				delegate: 'a.wpb_fp_preview',
				removalDelay: 500, //delay removal by X to allow out-animation
				callbacks: {
				    beforeOpen: function() {
				       this.st.mainClass = this.st.el.attr('data-effect');
				    }
				},
			  	closeOnContentClick: true,
			});


		});
	</script>

<?php
}
add_action( 'wp_footer','wpb_fp_magnific_popup_trigger' );


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
	#wpb_fp_filter_select li {
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

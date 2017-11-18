<?php
// Custom Post Type Setup
function tcportfolio_post_type() {
	$labels = array(
		'name' => __('All Portfolios', 'tc-Portfolio'),
		'singular_name' => __('TC Portfolio', 'tc-Portfolio'),
		'add_new' => __('Add New Portfolio', 'tc-Portfolio'),
		'all_items' => __('All Portfolios', 'tc-Portfolio' ),
		'add_new_item' => __('Add New Portfolio', 'tc-Portfolio'),
		'edit_item' => __('Edit Portfolio', 'tc-Portfolio'),
		'new_item' => __('New Portfolio', 'tc-Portfolio'),
		'view_item' => __('View Portfolio', 'tc-Portfolio'),
		'search_items' => __('Search Portfolio', 'tc-Portfolio'),
		'not_found' => __('No Portfolio', 'tc-Portfolio'),
		'not_found_in_trash' => __('No Portfolio found in Trash', 'tc-Portfolio'),
		'parent_item_colon' => '',
		'menu_name' => __('TC Portfolio', 'tc-Portfolio') // this name will be shown on the menu
	);

	$args = array(
		'labels' => $labels,
		'has_archive' => true,
		'supports' => array('title','thumbnail','editor','excerpt'),
		'taxonomies' => array( '' ),
		'public' => true,
		'capability_type' => 'post',
		'rewrite' => array( 'slug' => 'tcportfolio' ),
		'menu_position' =>5,
		'menu_icon' =>'dashicons-portfolio',

	);
	register_post_type('tcportfolio', $args);
}
 add_action( 'init', 'tcportfolio_post_type' );

// Adding a taxonomy for the Portfolio post type

function tcportfolio_taxonomy() {
	  $args = array('hierarchical' => true);
		register_taxonomy( 'tcportfolio_category', 'tcportfolio', $args );
	}
 add_action( 'init', 'tcportfolio_taxonomy', 0 );



  // Ad for PRO version

 function tcportfolio_pro_add_meta_box() {

 		add_meta_box(
 			'tcportfolio_sectionid_pro',
 			__( "TC Portfolio- PRO" , 'tc-Portfolio' ),
 			'tcportfolio_meta_box_pro',
 			'tcportfolio'
 		);
 }
 add_action( 'add_meta_boxes', 'tcportfolio_pro_add_meta_box' );

 function tcportfolio_meta_box_pro() {  ?>

 	<p>
 	<h3 style="padding-left:0">Available features at OWL Carousel WP - PRO</h3>
     <ol class="pro-features">
       <li>Transparent Hover Overly color.</li>
 			<li>Multiple column 2,3,4,5.</li>
 	    <li>Light-box- 2 light box pop up styles..</li>
 	    <li> Shortcodes Generator.</li>
 	    <li> Different Styling Option For Blog Post Carousel.</li>
 	    <li> Light Box effect.</li>
 			<li>Two Pop Up Light box Style</li>
 	    <li>2 Navigation position.</li>
			<li>Single Portfolio Page Template.</li>
			<li>Related/same category Portfolio item in single Page Template.</li>
			<li>Advanced Setting Panel</li>
			<li> Support within 6 hours.</li>
 	    <li>And many more…..</li>
     </ol>
   </p>
   <p><a class="tc-button tc-btn-red"
     target="_blank" href="https://www.themescode.com/items/tc-portfolio-pro/">Upgrade To PRO</a></p>
 <?php
 }

 // Learn WordPress with wpbrim

 function tcportfolio_side_meta_box() {
 	add_meta_box(
 		'tcportfolio_sidebar',
 		__( "Important Links" , 'tc-Portfolio' ),
 		'tcportfolio_link',
 		'tcportfolio',
 		'side',
 		'low'
 	);
 }
 add_action( 'add_meta_boxes', 'tcportfolio_side_meta_box' );

 function tcportfolio_link() { ?>
 	 <p></p>
<p><a class="tc-button tc-btn-blue" href="https://www.themescode.com/items/tc-portfolio-pro/" target="_blank">Plugin Home</a></p>
 	<p><a class="tc-button tc-btn-green" href="https://www.youtube.com/channel/UCgSU9WrGzHMTWnOavBArj1g" target="_blank">Watch Video Tutorials</a></p>
 	<p><a class="tc-button tc-btn-orange" href="http://portfolio.themescode.com/" target="_blank">Live Demo</a></p>
 	<p><a class="tc-button tc-btn-lime" href="http://docs.themescode.com/tc-portfolio-doc/" target="_blank">Documentation</a></p>

 	<div style="clear:both"></div>

 <?php
 }

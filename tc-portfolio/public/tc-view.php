<?php
function tcp_option( $option, $section, $default = '' ) {

    $options = get_option( $section );

    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }

    return $default;
}

function tcp_style_trigger($border){
  $tcp_mbgc=tcp_option('menu-bg-color','tc_portfolio_style', '#ff7055' );
  $tcp_overlay_c=tcp_option('img-overlay-color','tc_portfolio_style', '#ff7055' );
?>
<style media="screen">
ul.tcportfolio_filters  li {
    border: 1px solid <?php echo $tcp_mbgc; ?>;
}
ul.tcportfolio_filters  li:hover , ul.tcportfolio_filters  li.active{
    background:<?php echo $tcp_mbgc; ?>;
    color: #fff;
}
.tc_overlay{
    background-color:<?php echo $tcp_overlay_c; ?>;
}

</style>

<?php
}
add_action('wp_footer','tcp_style_trigger');

function tcportfolio_shortcode( $atts ) {

	// items
  $tcp_menutext=tcp_option('all_items_val','tc_portfolio_basics', 'All Items' );
  $tcp_showm=tcp_option('filter_menu','tc_portfolio_basics', 'yes' );
  $tcp_show_sd=tcp_option('short-description','tc_portfolio_basics','yes');
	// Attributes
	extract( shortcode_atts(
		array(
			'posts_num' => "-1",
			'order' => 'DESC',
			'orderby' => '',
			'portfolio_cat'=>'',

		), $atts )
	);
  $args = array(
      'orderby' => 'date',
       'order' => $order,
        'tc_category' =>$portfolio_cat,
        'showposts' => $posts_num,
        'post_type' => 'tcportfolio'
 );

 if($tcp_showm=='yes'){
?>
<section class="tcportfolio_area">
 <ul class="tcportfolio_filters">
     <?php
         $tcportfolio_terms = get_terms('tcportfolio_category');
         $tcportfolio_count = count($tcportfolio_terms);
              echo '<li class="active" data-filter="*">'.$tcp_menutext.'</li>';
         if ( $tcportfolio_count > 0 ){
             foreach ( $tcportfolio_terms as $tcportfolio_term ) {
                 $tcportfolio_termname = strtolower($tcportfolio_term->name);
                 $tcportfolio_termname = str_replace(' ', '-', $tcportfolio_termname);
                echo '<li  data-filter=".'.$tcportfolio_termname.'">'.$tcportfolio_term->name.'</li>';
             }
         }
     ?>
 </ul>
 </section>
<?php
}
 $tc_loop= new WP_Query( $args );
   global $post;
	 $tc_view='';
	 $tc_view.='<div class="tcportfolio-container">';
 		if ($tc_loop->have_posts()) :
 			while ($tc_loop->have_posts()) : $tc_loop->the_post();  // wb portfolio start
			   // add terms
					 $terms = get_the_terms( $post->ID, 'tcportfolio_category' );
									 if ( $terms && ! is_wp_error( $terms ) ) :
											 $links = array();
											 foreach ( $terms as $term ) {
													 $links[] = $term->name;
											 }
											 $tax_links = join( " ", str_replace(' ', '-', $links));
											 $tax = strtolower($tax_links);
									 else :
								 $tax = '';
							 endif;
				 // end add terms

      if ( has_post_thumbnail() ) {  // check if the post has a Post Thumbnail assigned to it.
					$tc_view.='<div class="tcportfolio_single_items '. $tax .'">';
						            $tc_view.='<img class="tcportfolio_cover" src="'.get_the_post_thumbnail_url().'" alt="" />';
                  $tc_view.='<div class="tc_overlay">';
						           $tc_view.='<h3 class="tcp-title"><a class="tcp-link" href="'.get_the_permalink().'" > '.get_the_title().' </a> </h3>';
                           if($tcp_show_sd=='yes'){
                           $tc_view.='<p class="tcp-short-des">'.themescode_limit_text(get_the_excerpt(),20).'</p>';
                            }
                             $tc_view.='<div class="tcp_links">';
                                $tc_view.='<a class="tcpc-link tcp-ext" href="'.get_the_permalink().'"><i class="fa fa-external-link" aria-hidden="true"></i></a>';
                                $tc_view.='<a class="tcpc-link tcp-view tc_owl_pop open-popup-link" href="#tc_owl_pop_'.get_the_id().'" data-effect="mfp-zoom-in"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                              $tc_view.='</div>'; // tcp_links
                  $tc_view.='</div>'; // tc_overlay
                    ?>

                  <div id="tc_owl_pop_<?php echo get_the_id(); ?>" class="tc-owl-white-popup mfp-hide mfp-with-anim">
                    <?php
                        $tc_owl_thumbnail_popup = get_the_post_thumbnail(get_the_ID(), 'full', array( 'class' =>'tc-owlpop-wps-img' ));
                        echo  $tc_owl_thumbnail_popup;
                     ?>

                  </div>   <!-- end show popup -->
                  <?php

	       $tc_view.='</div>'; // tcportfolio_items
        }
 		endwhile; //
 		endif;
	$tc_view.='</div>'; //tcportfolio-container;
 	wp_reset_query();
 	return $tc_view;
}
add_shortcode('tc-portfolio', 'tcportfolio_shortcode' );

?>

<?php

add_filter('manage_edit-tcportfolio_columns', 'add_new_tcportfolio_columns');
function add_new_tcportfolio_columns($tcportfolio_columns) {
  $new_columns= array(
    'cb' => '<input type="checkbox" />',
    'title' => __( 'Title' ),
    'featured_image' => __( 'Portfolio Images' ),
    'portfolio_cat'=>_('Portfolio Category'),
    'author' => __( 'Author' ),
    'date' => __( 'Date' )
  );
    return $new_columns;
}
add_action('manage_tcportfolio_posts_custom_column', 'manage_tcportfolio_columns', 10, 2);
function get_tcportfolio($post_ID)
{
    $tcportfolio_id = get_post_thumbnail_id($post_ID);
    return $tcportfolio_url = wp_get_attachment_image_src($tcportfolio_id, array(40,40), true);
}
function manage_tcportfolio_columns( $column,$post_ID) {
  $tcportfolio=get_tcportfolio($post_ID);
    switch ( $column ) {
	case 'featured_image' :
		global $post;
		$slug = '' ;
		$slug = $post->ID;
    $featured_image ='<img src="' . $tcportfolio[0] . '" width="90px"/>';
    echo $featured_image;
    break;
  case 'portfolio_cat' :
   $tcportfolio_cats = wp_get_post_terms($post_ID, 'tcportfolio_category', array("fields" => "names"));
     foreach ( $tcportfolio_cats as $tcportfolio_cat ) {
           echo $tcportfolio_cat.'<br>';

   }
    break;
    }
}

 ?>

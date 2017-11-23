<?php

/*
	WPB Filterable Portfolio
	By WPBean
	
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


//manage the columns of the "portfolio" post type

function wpb_fp_manage_columns_for_portfolio($columns){
 	
 	//remove columns
    unset($columns['date']);

    //add new columns
    $columns['portfolio_featured_image'] = __('Portfolio Featured Image',WPB_FP_TEXTDOMAIN);
    $columns['date'] = __('Date',WPB_FP_TEXTDOMAIN);
 
    return $columns;
}
add_action('manage_wpb_fp_portfolio_posts_columns','wpb_fp_manage_columns_for_portfolio');


//Populate custom columns for "portfolio" post type
function wpb_fp_populate_portfolio_columns($column,$post_id){
 
    //featured image column
    if($column == 'portfolio_featured_image'){
        //if this portfolio has a featured image
        if(has_post_thumbnail($post_id)){
            $portfolio_featured_image = get_the_post_thumbnail($post_id,array(100,100));
            echo $portfolio_featured_image;
        }else{
            echo __('This portfolio has no featured image',WPB_FP_TEXTDOMAIN); 
        }
    }
 
}
add_action('manage_wpb_fp_portfolio_posts_custom_column','wpb_fp_populate_portfolio_columns',10,2);
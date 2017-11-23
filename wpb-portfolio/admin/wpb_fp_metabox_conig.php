<?php 

/*
  WPB Filterable Portfolio
  By WPBean
  
*/

add_action( 'init', 'wpb_fp_metaboxes' );

function wpb_fp_metaboxes(){

  $prefix = 'wpb_fp_';
  $wpb_post_type_select = wpb_fp_get_option( 'wpb_fp_post_type_meta_support_', 'wpb_fp_advanced', array('wpb_fp_portfolio') );

  $fields = array(

    array(
      'label' => __( 'Portfolio External Link', WPB_FP_TEXTDOMAIN ),
      'desc'  => __( 'Portfolio external link, If not provided it will linking to single portfolio.', WPB_FP_TEXTDOMAIN ),
      'id'    => $prefix.'portfolio_ex_link',
      'type'  => 'text'
    ),
    array(
      'label' => __( 'Disable Overlay', WPB_FP_TEXTDOMAIN ),
      'desc'  => __( 'Portfolio grid can be disable for specific item.', WPB_FP_TEXTDOMAIN ),
      'id'    => $prefix.'disable_overlay',
      'type'  => 'checkbox'
    ),
    array(
      'label'   => __( 'Grid Content Type', WPB_FP_TEXTDOMAIN ),
      'desc'    => 'Select a content type for portfolio grid. Default: feature image.',
      'id'      => $prefix.'content_type',
      'type'    => 'select',
      'options' => array (
        'feature_image' => array (
          'label' => __( 'Feature Image', WPB_FP_TEXTDOMAIN ),
          'value' => 'feature_image'
        ),
        'video' => array (
          'label' => __( 'Video', WPB_FP_TEXTDOMAIN ),
          'value' => 'video'
        ),
      )
    ),
    array(
      'label'     => __( 'Video Iframe', WPB_FP_TEXTDOMAIN ),
      'desc'      => __( 'YouTube, Vimeo or any other video iframe', WPB_FP_TEXTDOMAIN ),
      'id'        => $prefix.'video_iframe',
      'type'      => 'textarea',
      'sanitizer' => 'no',
    ),
    array(
      'label'   => __( 'Quick View Content Type', WPB_FP_TEXTDOMAIN ),
      'desc'    => 'Select a content type for portfolio quick view popup. Default: feature image.',
      'id'      => $prefix.'quickview_content_type',
      'type'    => 'select',
      'options' => array (
        'feature_image' => array (
          'label' => __( 'Feature Image', WPB_FP_TEXTDOMAIN ),
          'value' => 'feature_image'
        ),
        'video' => array (
          'label' => __( 'Video', WPB_FP_TEXTDOMAIN ),
          'value' => 'video'
        ),
      )
    ),
    array(
      'label'     => __( 'Quick View Video Iframe', WPB_FP_TEXTDOMAIN ),
      'desc'      => __( 'Large iframe for quick view lightbox.', WPB_FP_TEXTDOMAIN ),
      'id'        => $prefix.'quickview_video_iframe',
      'type'      => 'textarea',
      'sanitizer' => 'no',
    ),
  );

  /**
   * Instantiate the class with all variables to create a meta box
   * var $id string meta box id
   * var $title string title
   * var $fields array fields
   * var $page string|array post type to add meta box to
   * var $context The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side'). 
   */

  $portfolio_metabox = new WPB_FP_Custom_Add_Meta_Box( 'portfolio_options', __( 'Portfolio Options',WPB_FP_TEXTDOMAIN ), apply_filters( 'wpb_fp_metabox', $fields, $prefix ), $wpb_post_type_select, 'normal' );

}
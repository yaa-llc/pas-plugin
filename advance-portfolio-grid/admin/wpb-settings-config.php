<?php

/*
    WPB Portfolio PRO
    By WPBean
    
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


/**
 * installing setting api class by wpbean
 */
if ( !class_exists('WPB_fp_settings_config' ) ):
class WPB_fp_settings_config {

    private $settings_api;

    function __construct() {
        $this->settings_api = new wpb_fp_WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }
    
    function admin_menu() {

        add_submenu_page( 
            'edit.php?post_type=wpb_fp_portfolio', 
            __( 'Portfolio Settings','wpb_fp' ),
            __( 'Portfolio Settings','wpb_fp' ),
            'delete_posts',
            'portfolio-settings',
            array($this, 'wpb_plugin_page')
        ); 

    }
    // setings tabs
    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'wpb_fp_general',
                'title' => __( 'General Settings', 'wpb_fp' )
            ),
            array(
                'id' => 'wpb_fp_advanced',
                'title' => __( 'Advanced Settings', 'wpb_fp' )
            ),
            array(
                'id' => 'wpb_fp_style',
                'title' => __( 'Style Settings', 'wpb_fp' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array( 
            
            'wpb_fp_general' => array(
                array(
                    'name'      => 'wpb_fp_column_',
                    'label'     => __( 'Column', 'wpb_fp' ),
                    'desc'      => __( 'Number of portfolio column.', 'wpb_fp' ),
                    'type'      => 'select',
                    'default'   => 4,
                    'options'   => array(
                        '3'     => '4 Column',
                        '4'     => '3 Column',
                        '6'     => '2 Column',
                    )
                ),
                array(
                    'name'      => 'wpb_fp_number_of_post_',
                    'label'     => __( 'Number of post', 'wpb_fp' ),
                    'desc'      => __( 'Number of post to show. Default -1, means show all.', 'wpb_fp' ),
                    'type'      => 'number',
                    'default'   => -1
                ),
            ),
            'wpb_fp_advanced' => array(
                array(
                    'name'      => 'wpb_post_type_select_',
                    'label'     => __( 'Post Type', 'wpb_fp' ),
                    'desc'      => __( 'You can select your own custom post type. Default: Our portfolio post type that come with plugin.', 'wpb_fp' ),
                    'type'      => 'select',
                    'default'   => 'wpb_fp_portfolio',
                    'options'   => wpb_fp_post_type_select(),
                ),
                array(
                    'name'      => 'wpb_taxonomy_select_',
                    'label'     => __( 'Taxonomy', 'wpb_fp' ),
                    'desc'      => __( 'You can select your own custom taxonomy ( taxonomy means custom category ).  Default: Our portfolio category that come with plugin.', 'wpb_fp' ),
                    'type'      => 'select',
                    'default'   => 'wpb_fp_portfolio_cat',
                    'options'   => wpb_fp_taxonomy_select(),
                ),
                array(
                    'name'      => 'wpb_fp_cat_exclude_',
                    'label'     => __( 'Exclude Taxonomies', 'wpb_fp' ),
                    'desc'      => __( 'You can exclude selected Taxonomies form portfolio.', 'wpb_fp' ),
                    'type'      => 'multicheck',
                    'options'   => wpb_fp_exclude_categories(),
                ),
                array(
                    'name'      => 'wpb_fp_image_width_',
                    'label'     => __( 'Image Width', 'wpb_fp' ),
                    'desc'      => __( 'Portfolio thumbnail width in Px. Minimum 200. Default 680', 'wpb_fp' ),
                    'type'      => 'number',
                    'min'       => 200,
                    'default'   => 680
                ),
                array(
                    'name'      => 'wpb_fp_image_height_',
                    'label'     => __( 'Image height', 'wpb_fp' ),
                    'desc'      => __( 'Portfolio thumbnail height in Px. Minimum 200. Default 680', 'wpb_fp' ),
                    'type'      => 'number',
                    'min'       => 200,
                    'default'   => 680
                ),
                array(
                    'name'      => 'wpb_fp_show_overlay_',
                    'label'     => __( 'Portfolio overlay', 'wpb_fp' ),
                    'desc'      => __( 'Portfolio overlay on mouse hover. Default: Show.', 'wpb_fp' ),
                    'type'      => 'radio',
                    'default'   => 'show',
                    'options'   => array(
                        'show'  => 'Show',
                        'hide'  => 'Hide'
                    )
                ),
                array(
                    'name'      => 'wpb_fp_show_links_',
                    'label'     => __( 'Portfolio overlay Links', 'wpb_fp' ),
                    'desc'      => __( 'Portfolio overlay on mouse hover showing two links. Default: Show.', 'wpb_fp' ),
                    'type'      => 'radio',
                    'default'   => 'show',
                    'options'   => array(
                        'show'  => 'Show',
                        'hide'  => 'Hide'
                    )
                ),
                array(
                    'name'      => 'wpb_fp_view_portfolio_btn_text_',
                    'label'     => __( 'View Portfolio Button Text', 'wpb_fp' ),
                    'desc'      => __( 'View portfolio button that allow you to link your external site or anything else. You can change that button text.', 'wpb_fp' ),
                    'type'      => 'text',
                    'default'   => 'View Portfolio'
                ),
            ),
            'wpb_fp_style' => array(
                array(
                    'name'      => 'wpb_fp_primary_color_',
                    'label'     => __( 'Primary color', 'wpb_fp' ),
                    'desc'      => __( 'Select your portfolio primary color. Default: #21cdec', 'wpb_fp' ),
                    'type'      => 'color',
                    'default'   => '#21cdec'
                ),
                array(
                    'name'      => 'wpb_fp_popup_effect_',
                    'label'     => __( 'Quick View Effect.', 'wpb_fp' ),
                    'desc'      => __( 'Select your Quick View Effect popup effect.', 'wpb_fp' ),
                    'type'      => 'select',
                    'default'   => 'mfp-zoom-in',
                    'options'   => array(
                        'mfp-zoom-in'           => 'Zoom effect',
                        'mfp-newspaper'         => 'Newspaper effect',
                        'mfp-move-horizontal'   => 'Move-horizontal effect',
                        'mfp-move-from-top'     => 'Move-from-top effect',
                        'mfp-3d-unfold'         => '3d unfold',
                        'mfp-zoom-out'          => 'Zoom-out effect',
                    ),
                ),
                array(
                    'name'      => 'wpb_fp_hover_effect_',
                    'label'     => __( 'Hover Effect.', 'wpb_fp' ),
                    'desc'      => __( 'Select an effect for mouse hover on portfolio.', 'wpb_fp' ),
                    'type'      => 'select',
                    'default'   => 'effect-layla',
                    'options'   => array(
                        'effect-roxy'     => 'Roxy',
                        'effect-bubba'    => 'Bubba',
                        'effect-marley'   => 'Marley',
                        'effect-oscar'    => 'Oscar',
                        'effect-layla'    => 'Layla',
                    ),
                ),
                array(
                    'name'      => 'wpb_fp_title_font_size_',
                    'label'     => __( 'Portfolio title font size.', 'wpb_fp' ),
                    'desc'      => __( 'Font size for portfolio title. Default 20px.', 'wpb_fp' ),
                    'type'      => 'number',
                    'default'   => 20
                ),
                array(
                    'name' => 'wpb_fp_custom_css_',
                    'label' => __( 'Portfolio Custom CSS', 'wpb_fp' ),
                    'desc' => __( 'You can write you own custom css code here.', 'wpb_fp' ),
                    'type' => 'textarea',
                    'rows' => 8
                ),

            ),
        );
        return $settings_fields;
    }
    
    // warping the settings
    function wpb_plugin_page() {
        ?>
            <?php do_action ( 'wpb_fp_before_settings' ); ?>
            <div class="wpb_fp_settings_area">
                <div class="wrap wpb_fp_settings">
                    <?php
                        $this->settings_api->show_navigation();
                        $this->settings_api->show_forms();
                    ?>
                </div>
                <div class="wpb_fp_settings_content">
                    <?php do_action ( 'wpb_fp_settings_content' ); ?>
                </div>
            </div>
            <?php do_action ( 'wpb_fp_after_settings' ); ?>
        <?php
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }
        return $pages_options;
    }
}
endif;

$settings = new WPB_fp_settings_config();


//--------- trigger setting api class---------------- //

function wpb_fp_get_option( $option, $section, $default = '' ) {
 
    $options = get_option( $section );
 
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
 
    return $default;
}
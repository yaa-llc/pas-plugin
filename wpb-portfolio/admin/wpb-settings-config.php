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
            __( 'Portfolio Settings',WPB_FP_TEXTDOMAIN ),
            __( 'Portfolio Settings',WPB_FP_TEXTDOMAIN ),
            'delete_posts',
            'portfolio-settings',
            array($this, 'wpb_plugin_page')
        ); 

    }
    // setings tabs
    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'wpb_fp_general',
                'title' => __( 'General Settings', WPB_FP_TEXTDOMAIN )
            ),
            array(
                'id'    => 'wpb_fp_advanced',
                'title' => __( 'Advanced Settings', WPB_FP_TEXTDOMAIN )
            ),
            array(
                'id'    => 'wpb_fp_style',
                'title' => __( 'Style Settings', WPB_FP_TEXTDOMAIN )
            )
        );
        $sections = apply_filters( 'wpb_fp_settings_sections', $sections );
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
                    'label'     => __( 'Columns', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Number of portfolio column.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'select',
                    'default'   => 4,
                    'options'   => array(
                        '3'     => '4 Columns',
                        '4'     => '3 Columns',
                        '6'     => '2 Columns',
                    )
                ),
                array(
                    'name'      => 'wpb_fp_filter_position_',
                    'label'     => __( 'Filter Position', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Portfolio filter position. Options: left, right, center. Default: left.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'select',
                    'default'   => 'center',
                    'options'   => array(
                        'left'     => 'Left',
                        'center'   => 'Center',
                        'right'    => 'Right',
                    )
                ),
                array(
                    'name'      => 'wpb_fp_show_counting_',
                    'label'     => __( 'Counting post', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Portfolio filter on mouse hover show number of post avaiable. Default: Show.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'radio',
                    'default'   => 'show',
                    'options'   => array(
                        'show'  => 'Show',
                        'hide'  => 'Hide'
                    )
                ),
                array(
                    'name'      => 'wpb_fp_number_of_post_',
                    'label'     => __( 'Number of post', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Number of post to show. Default -1, means show all.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'number',
                    'default'   => -1
                ),
                array(
                    'name'  => 'wpb_fp_gallery_support',
                    'label' => __( 'Portfolio Gallery Support', WPB_FP_TEXTDOMAIN ),
                    'desc'  => __( 'No.', WPB_FP_TEXTDOMAIN ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'      => 'wpb_fp_gallery_images_in_portfolio_page',
                    'label'     => __( 'Show Gallery Images in Single Portfolio Page', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Yes.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'checkbox',
                    'default'   => 'on'
                ),
                array(
                    'name'      => 'wpb_fp_video_in_portfolio_page',
                    'label'     => __( 'Show Video in Single Portfolio Page', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Yes.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'checkbox',
                    'default'   => 'on'
                ),
                array(
                    'name'      => 'wpb_fp_title_character_limit_',
                    'label'     => __( 'Portfolio Title Character Limit', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Yes.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'checkbox',
                    'default'   => 'on',
                ),
                array(
                    'name'      => 'wpb_fp_pagination',
                    'label'     => __( 'Portfolio Pagination', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Yes.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'checkbox',
                    'default'   => 'on',
                ),
                array(
                    'name'      => 'wpb_fp_number_of_title_character',
                    'label'     => __( 'Number of Characters in Title', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Number of characters in title to show. Default 16. You have to must check Portfolio Title Character Limit to function this limit.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'number',
                    'default'   => 16
                ),
                array(
                    'name'      => 'wpb_fp_after_title',
                    'label'     => __( 'After Title Content', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'If checked Portfolio Title Character Limit, the title will be cut off and will be add this content after the title.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'text',
                    'default'   => '...'
                ),
                array(
                    'name'      => 'wpb_fp_all_btn_text',
                    'label'     => __( 'Filter All Button Text', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Portfolio filter all button text.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'text',
                    'default'   => __( 'All', WPB_FP_TEXTDOMAIN ),
                ),
            ),
            'wpb_fp_advanced' => array(
                array(
                    'name'      => 'wpb_post_type_select_',
                    'label'     => __( 'Post Type', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'You can select your own custom post type. Default: Our portfolio post type that come with plugin.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'select',
                    'default'   => 'wpb_fp_portfolio',
                    'options'   => wpb_fp_post_type_select(),
                ),
                array(
                    'name'      => 'wpb_taxonomy_select_',
                    'label'     => __( 'Taxonomy', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'You can select your own custom taxonomy ( taxonomy means custom category ).  Default: Our portfolio category that come with plugin.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'select',
                    'default'   => 'wpb_fp_portfolio_cat',
                    'options'   => wpb_fp_taxonomy_select(),
                ),
                array(
                    'name'      => 'wpb_fp_post_type_meta_support_',
                    'label'     => __( 'Post type portfolio options support', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Select post types you want to add portfolio options (meta box) support.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'multicheck',
                    'default'   => array( 'wpb_fp_portfolio' => 'wpb_fp_portfolio' ),
                    'options'   => wpb_fp_post_type_multicheck_option(),
                ),
                array(
                    'name'      => 'wpb_fp_cat_exclude_',
                    'label'     => __( 'Exclude categories', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'You can exclude selected categories form portfolio.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'multicheck',
                    'options'   => wpb_fp_exclude_categories(),
                ),
                array(
                    'name'      => 'wpb_fp_image_width_',
                    'label'     => __( 'Image Width', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Portfolio thumbnail width in Px. Minimum 200. Default 680', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'number',
                    'min'       => 200,
                    'default'   => 680
                ),
                array(
                    'name'      => 'wpb_fp_image_height_',
                    'label'     => __( 'Image height', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Portfolio thumbnail height in Px. Minimum 200. Default 680', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'number',
                    'min'       => 200,
                    'default'   => 680
                ),
                array(
                    'name'      => 'wpb_fp_image_hard_crop_',
                    'label'     => __( 'Image Hard Crop', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'If you disable / hide the hard crop the images is not going to crop form the shortcode builder, you can only set the images width and height here in the settings. And you have to regenerate thumbnails everytime you change those.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'radio',
                    'default'   => 'yes',
                    'options'   => array(
                        'yes'   => 'Yes',
                        'no'    => 'No'
                    )
                ),
                array(
                    'name'      => 'wpb_fp_show_overlay_',
                    'label'     => __( 'Portfolio overlay', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Portfolio overlay on mouse hover. Default: Show.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'radio',
                    'default'   => 'show',
                    'options'   => array(
                        'show'  => 'Show',
                        'hide'  => 'Hide'
                    )
                ),
                array(
                    'name'      => 'wpb_fp_link_full_grid_',
                    'label'     => __( 'Full Grid Linking', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'If you disable / hide the overlay on mouse hover the grid, you may want to link full grid itself.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'radio',
                    'default'   => 'no',
                    'options'   => array(
                        'yes'   => 'Yes',
                        'no'    => 'No'
                    )
                ),
                array(
                    'name'      => 'wpb_fp_link_full_grid_type_',
                    'label'     => __( 'Full Grid Link type', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'If you disable / hide the overlay on mouse hover the grid and enable the full grid linking, you may want to link either portfolio details page or qiickview popup.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'radio',
                    'default'   => 'details_page',
                    'options'   => array(
                        'details_page'      => 'Portfolio details / External URL',
                        'quickview_popup'   => 'QuickView Popup'
                    )
                ),
                array(
                    'name'      => 'wpb_fp_quickview_icon',
                    'label'     => __( 'Portfolio Quick View Icon', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Portfolio on mouse hover showing quick view link. Default: Show.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'radio',
                    'default'   => 'show',
                    'options'   => array(
                        'show'  => 'Show',
                        'hide'  => 'Hide'
                    )
                ),
                array(
                    'name'      => 'wpb_fp_single_portfolio_link',
                    'label'     => __( 'Portfolio Single Page Link Icon', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Portfolio on mouse hover showing single portfolio page. If set to hide, it will only show the icon when portfolio has external link set.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'radio',
                    'default'   => 'show',
                    'options'   => array(
                        'show'  => 'Show',
                        'hide'  => 'Hide'
                    )
                ),
                array(
                    'name'      => 'wpb_fp_filtering',
                    'label'     => __( 'Portfolio Filtering', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Portfolio filtering can be enable or disable. Default: Enable.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'radio',
                    'default'   => 'enable',
                    'options'   => array(
                        'enable'    => 'Enable',
                        'disable'   => 'Disable'
                    )
                ),
                array(
                    'name'      => 'wpb_fp_view_portfolio_btn_text_',
                    'label'     => __( 'View Portfolio Button Text', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'View portfolio button that allow you to link your external site or anything else. You can change that button text.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'text',
                    'default'   => 'View Portfolio'
                ),
                array(
                    'name'      => 'wpb_fp_view_portfolio_btn_target',
                    'label'     => __( 'View Portfolio Button Target.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'select',
                    'default'   => 'new',
                    'options'   => array(
                        'new'     => 'New Window',
                        'same'    => 'Same Window',
                    ),
                ),
                array(
                    'name'      => 'wpb_fp_portfolio_slug_',
                    'label'     => __( 'Portfolio Slug', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'After updating the portfolio slug, make sure you Flush Rewrite Rules. You can use <a href="https://wordpress.org/plugins/rewrite-flush-button/" target="_blank">this plugin</a> for doing this. If you not, portfolio link may show you NOT Found or 404 NOT FOUND', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'text',
                    'default'   => 'all_portfolio'
                ),
            ),
            'wpb_fp_style' => array(
                array(
                    'name'      => 'wpb_fp_primary_color_',
                    'label'     => __( 'Primary color', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Select your portfolio primary color. Default: #21cdec', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'color',
                    'default'   => '#21cdec'
                ),
                array(
                    'name'      => 'wpb_fp_popup_effect_',
                    'label'     => __( 'Quick View Effect.', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Select your Quick View Effect popup effect.', WPB_FP_TEXTDOMAIN ),
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
                    'label'     => __( 'Hover Effect.', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Select an effect for mouse hover on portfolio.', WPB_FP_TEXTDOMAIN ),
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
                    'name'      => 'wpb_fp_filter_style_',
                    'label'     => __( 'Filter style', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Select a style for portfolio filter.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'select',
                    'default'   => 'default',
                    'options'   => array(
                        'default'     => 'Default',
                        'capsule'     => 'Capsule',
                        'Select'      => 'Select Box',
                    ),
                ),
                array(
                    'name'      => 'wpb_fp_title_font_size_',
                    'label'     => __( 'Portfolio title font size.', WPB_FP_TEXTDOMAIN ),
                    'desc'      => __( 'Font size for portfolio title. Default 20px.', WPB_FP_TEXTDOMAIN ),
                    'type'      => 'number',
                    'default'   => 20
                ),
                array(
                    'name' => 'wpb_fp_custom_css_',
                    'label' => __( 'Portfolio Custom CSS', WPB_FP_TEXTDOMAIN ),
                    'desc' => __( 'You can write you own custom css code here.', WPB_FP_TEXTDOMAIN ),
                    'type' => 'textarea',
                    'rows' => 8
                ),
                array(
                    'name'  => 'wpb_fp_load_fa_icon',
                    'label' => __( 'Load Font Awesome ?', WPB_FP_TEXTDOMAIN ),
                    'desc'  => __( 'No.', WPB_FP_TEXTDOMAIN ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'  => 'wpb_fp_load_magnific_popup',
                    'label' => __( 'Load Magnific Popup ?', WPB_FP_TEXTDOMAIN ),
                    'desc'  => __( 'No.', WPB_FP_TEXTDOMAIN ),
                    'type'  => 'checkbox'
                ),

            ),
        );
        
        $settings_fields = apply_filters( 'wpb_fp_settings_fields', $settings_fields );

        return $settings_fields;
    }
    
    // warping the settings
    function wpb_plugin_page() {
        echo '<div class="wrap wpb_fp_wrap">';
            settings_errors();
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
        echo '</div>';
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
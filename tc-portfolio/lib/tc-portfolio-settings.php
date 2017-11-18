<?php

/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */
if ( !class_exists('themesCode_Settings_API_Test' ) ):
class themesCode_Settings_API_Test {

    private $settings_api;

    function __construct() {
        $this->settings_api = new themesCode_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        //add_action( 'admin_menu', array($this, 'admin_menu') );
        add_action( 'admin_menu', array($this, 'sub_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( 'edit.php?post_type=tcportfolio','Settings API', 'Settings API', 'delete_posts', 'settings_api_test', array($this, 'plugin_page') );
    }


     function sub_menu()
    {
      add_submenu_page( 'edit.php?post_type=tcportfolio','Portfolio Settings','Portfolio Settings', 'manage_options','tcportfolio-settings',array($this, 'plugin_page'));
    }


    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'tc_portfolio_basics',
                'title' => __( 'Basic Settings', 'tc-portfolio' )
            ),

           array(
                'id'    => 'tc_portfolio_style',
                'title' => __( 'Style Settings', 'tc-portfolio' )
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
            'tc_portfolio_basics' => array(
                array(
                    'name'              => 'all_items_val',
                    'label'             => __( 'All Items Menu Text', 'tc-portfolio' ),
                    'desc'              => __( 'All Items Menu Text', 'tc-portfolio' ),
                    'placeholder'       => __( 'All Items', 'tc-portfolio' ),
                    'type'              => 'text',
                    'default'           => 'All Items',
                    'sanitize_callback' => 'sanitize_text_field'
                ),

                array(
                    'name'    => 'filter_menu',
                    'label'   => __( 'Show Filter Menu', 'tc-portfolio' ),
                    'desc'    => __( 'Show Filter Menu', 'tc-portfolio' ),
                    'type'    => 'radio',
                    'default' => 'yes',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),

                array(
                    'name'    => 'short-description',
                    'label'   => __( 'Show Short Description ', 'tc-portfolio' ),
                    'desc'    => __( 'Show Short Description On Hover', 'tc-portfolio' ),
                    'type'    => 'radio',
                    'default' => 'yes',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),


            ),

        'tc_portfolio_style' => array(
                array(
                    'name'    => 'menu-bg-color',
                    'label'   => __( 'Menu Color', 'tc-portfolio' ),
                    'desc'    => __( 'Filter Menu Background Color', 'tc-portfolio' ),
                    'type'    => 'color',
                    'default' => '#ff7055'
                ),
                array(
                    'name'    => 'img-overlay-color',
                    'label'   => __( 'Image Overlay Color', 'tc-portfolio' ),
                    'desc'    => __( 'Portfolio Image Overlay Hover Color', 'tc-portfolio' ),
                    'type'    => 'color',
                    'default' => '#ff7055'
                ),

           )
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

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

<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.huebscheseiten.de
 * @since      1.0.0
 *
 * @package    Jub_Portfolio_Gallery
 * @subpackage Jub_Portfolio_Gallery/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Jub_Portfolio_Gallery
 * @subpackage Jub_Portfolio_Gallery/admin
 * @author     Jan Müller <jan.m@jungundbillig.de>
 */
class Jub_Portfolio_Gallery_Admin
{

    /**
     * The plugin options.
     *
     * @since        1.0.0
     * @access        private
     * @var        string $options The plugin options.
     */
    private $options;

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->set_options();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Jub_Portfolio_Gallery_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Jub_Portfolio_Gallery_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/jub-portfolio-gallery-admin.css', array(), $this->version);

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Jub_Portfolio_Gallery_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Jub_Portfolio_Gallery_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        //load masonry library to display grid in grid configuration

        wp_register_script('images-loaded', plugins_url() . '/' . $this->plugin_name . '/public/js/images-loaded.js', array('jquery'), $this->version, true);
        wp_enqueue_script('images-loaded');
        wp_register_script('masonry', plugins_url() . '/' . $this->plugin_name . '/public/js/masonry.js', array('jquery', 'images-loaded'), $this->version, false);
        wp_enqueue_script('masonry');
        wp_enqueue_script('gallery-admin', plugin_dir_url(__FILE__) . 'js/jub-portfolio-gallery-admin.js', array('jquery', 'wp-color-picker', 'masonry'), $this->version, true);


    }

    /**
     * Sets the class variable $options
     */
    private function set_options()
    {

        $this->options = get_option($this->plugin_name . '-options');

    } // set_options()


    /**
     * Returns an array of options names, fields types, and default values
     * Is used during plugin activation to set default values in the db
     *
     * @return        array            An array of options
     */
    public static function get_options_list()
    {
        $options = array();

        //Layout
        $options[] = array('cols', 'select', '3');
        $options[] = array('cols-tablet', 'select', '2');
        $options[] = array('cols-tablet', 'select', '1');
        $options[] = array('x_dist', 'text', '1');
        $options[] = array('y_dist', 'text', '1');
        $options[] = array('image_size', 'select', 'full');
        $options[] = array('display_title_below', 'checkbox', "0");
        $options[] = array('display_excerpt_below', 'checkbox', "0");
        $options[] = array('background_color', 'text', '');
        $options[] = array('enable_lightbox', 'checkbox', "0");
        $options[] = array('open_in_new_window', 'checkbox', "0");
        $options[] = array('included_tags', 'text', '');


        //Rollover
        $options[] = array('hover_class', 'select', '');
        $options[] = array('overlay_hide_text', 'checkbox', "0");
        $options[] = array('overlay_hide_excerpt', 'checkbox', "0");
        $options[] = array('overlay_animation_class', 'select', 'fade');
        $options[] = array('no_overlay', 'checkbox', "0");
        $options[] = array('overlay_initial_color', 'text', '#e67e22');
        $options[] = array('overlay_initial_opacity', 'text', '0.0');
        $options[] = array('overlay_initial_font_opacity', 'text', '0.0');

        $options[] = array('overlay_color', 'text', '#e67e22');
        $options[] = array('overlay_opacity', 'text', '1');
        $options[] = array('overlay_font_opacity', 'text', '1');
        $options[] = array('overlay_font_size', 'text', '22');
        $options[] = array('overlay_text_color', 'text', '#fff');

        //Filter
        $options[] = array('hide_filter', 'checkbox', "0");
        $options[] = array('filter_button_color', 'text', '#000');
        $options[] = array('filter_button_hover_color', 'text', '#34495e');
        $options[] = array('filter-button-text-color', 'text', 'white');
        $options[] = array('filter-button-text-hover-color', 'text', 'white');
        $options[] = array('filter-position', 'select', 'center');
        $options[] = array('all_button_text', 'text', 'All');

        return $options;
    }


    public static function init_portfolio_post_type()
    {

        $labels = array(
            'name' => __('All Portfolios', 'jub-portfolio-gallery'),
            'singular_name' => __('JB Portfolio', 'jub-portfolio-gallery'),
            'add_new' => __('Add New Portfolio', 'jub-portfolio-gallery'),
            'all_items' => __('All Portfolios', 'jub-portfolio-gallery'),
            'add_new_item' => __('Add New Portfolio', 'jub-portfolio-gallery'),
            'edit_item' => __('Edit Portfolio', 'jub-portfolio-gallery'),
            'new_item' => __('New Portfolio', 'jub-portfolio-gallery'),
            'view_item' => __('View Portfolio', 'jub-portfolio-gallery'),
            'search_items' => __('Search Portfolio', 'jub-portfolio-gallery'),
            'not_found' => __('No Portfolio', 'jub-portfolio-gallery'),
            'not_found_in_trash' => __('No Portfolio found in Trash', 'jub-portfolio-gallery'),
            'parent_item_colon' => '',
            'menu_name' => __('JUB Portfolio', 'jub-portfolio-gallery')
        );

        $args = array(
            'labels' => $labels,
            'has_archive' => true,
            'supports' => array('title', 'thumbnail', 'editor', 'excerpt', 'supports', 'page-attributes'),
            'taxonomies' => array('a'),
            'public' => true,
            'capability_type' => 'post',

            'menu_position' => 5,
            'menu_icon' => 'dashicons-format-gallery',
        );

        register_post_type('jubportfolio', $args);
    }

    public static function init_jubportfolio_taxonomy()
    {
        $args = array(
            'hierarchical' => false
        );

        register_taxonomy('jubportfolio_category', 'jubportfolio', $args);
    }

    /**
     * Adds a settings page link to a menu
     *
     * @link        https://codex.wordpress.org/Administration_Menus
     * @since        1.0.0
     * @return        void
     */
    public function add_menu()
    {

        // Top-level page
        // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

        // Submenu Page
        // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);

        add_submenu_page(
            'edit.php?post_type=jubportfolio',
            apply_filters($this->plugin_name . '-settings-page-title', esc_html__('Portfolio-Settings', 'jub-portfolio-gallery')),
            apply_filters($this->plugin_name . '-settings-menu-title', esc_html__('Settings', 'jub-portfolio')),
            'manage_options',
            $this->plugin_name . '-settings',
            array($this, 'page_options')
        );

        add_submenu_page(
            'edit.php?post_type=jubportfolio',
            apply_filters($this->plugin_name . '-settings-page-title', esc_html__('Help-Page', 'jub-portfolio-gallery')),
            apply_filters($this->plugin_name . '-settings-menu-title', esc_html__('Help', 'jub-portfolio-gallery')),
            'manage_options',
            $this->plugin_name . '-help',
            array($this, 'page_help')
        );

        

    }

    /**
     * Creates the options page
     *
     * @since        1.0.0
     * @return        void
     */
    public function page_options()
    {
        include(plugin_dir_path(__FILE__) . 'partials/jub-portfolio-gallery-admin-settings.php');
    }

    public function page_help()
    {
        include(plugin_dir_path(__FILE__) . 'partials/jub-portfolio-gallery-admin-help.php');
    }

    /**
     * Registers plugin settings
     *
     * @since        1.0.0
     * @return        void
     */
    public function register_settings()
    {
        // register_setting( $option_group, $option_name, $sanitize_callback );

        register_setting(
            $this->plugin_name . '-options',
            $this->plugin_name . '-options'
//            array($this, 'validate_options')
        );
    }


    /**
     * Registers settings sections with WordPress
     *
     */
    public function register_sections()
    {

        //add_settings_section( $id, $title, $callback, $page );
        add_settings_section(
            $this->plugin_name . '-layout',
            apply_filters($this->plugin_name . 'section-title-messages', esc_html__('Layout', 'sections-layout-options')),
            array($this, 'section_layout'),
            $this->plugin_name
        );

        add_settings_section(
            $this->plugin_name . '-rollover',
            apply_filters($this->plugin_name . 'section-rollover-options', esc_html__('Rollover', 'section-rollover-options')),
            array($this, 'section_rollover'),
            $this->plugin_name
        );

        add_settings_section(
            $this->plugin_name . '-filter',
            apply_filters($this->plugin_name . 'section-filter-options', esc_html__('Filter', 'section-filter-options')),
            array($this, 'section_filter'),
            $this->plugin_name
        );

        add_settings_section(
            $this->plugin_name . '-grid',
            apply_filters($this->plugin_name . 'section-grid-options', esc_html__('Items', 'section-grid-options')),
            array($this, 'section_grid'),
            $this->plugin_name
        );
    }

    /**
     * Creates a rollover section
     *
     * @since        1.0.0
     * @param        array $params Array of parameters for the section
     * @return        mixed                        The settings section
     */
    public function section_rollover($params)
    {

        include(plugin_dir_path(__FILE__) . 'partials/jub-portfolio-gallery-admin-section-rollover-options.php');

    }

    /**
     * Creates a settings section
     *
     * @since        1.0.0
     * @param        array $params Array of parameters for the section
     * @return        mixed                        The settings section
     */
    public function section_layout($params)
    {
        include(plugin_dir_path(__FILE__) . 'partials/jub-portfolio-gallery-admin-section-layout-options.php');
    }

    public function section_filter($params)
    {
        include(plugin_dir_path(__FILE__) . 'partials/jub-portfolio-gallery-admin-section-filter-options.php');
    }

    public function section_grid($params)
    {
        include(plugin_dir_path(__FILE__) . 'partials/jub-portfolio-gallery-admin-section-grid-options.php');
    }


    /**
     * Registers settings fields with WordPress
     */
    public function register_fields()
    {
        //LAYOUT-OPTIONS

        // add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );



        add_settings_field(
            'cols-dropdown', //id
            apply_filters($this->plugin_name . 'label-hover-class-dropdown', esc_html__('Columns', 'jub-portfolio-gallery')), //title
            array($this, 'field_select'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-layout', //section
            array(
                'id' => 'cols',
                'selections' => array(
                    '1',
                    '2',
                    '3',
                    '4',
                    '5',
                    '6',
                    '7',
                    '8',
                    '9'
                ),
                'value' => '3',
            )
        );

        add_settings_field(
            'cols-tablet-dropdown', //id
            apply_filters($this->plugin_name . 'label-hover-class-dropdown', esc_html__('Columns Tablet', 'jub-portfolio-gallery')), //title
            array($this, 'field_select'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-layout', //section
            array(
                'id' => 'cols_tablet',
                'selections' => array(
                    '1',
                    '2',
                    '3',
                    '4',
                    '5',
                    '6',
                    '7',
                    '8',
                    '9'
                ),
                'value' => '2',
            )
        );

        add_settings_field(
            'cols-mobile-dropdown', //id
            apply_filters($this->plugin_name . 'label-hover-class-dropdown', esc_html__('Columns Mobile', 'jub-portfolio-gallery')), //title
            array($this, 'field_select'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-layout', //section
            array(
                'id' => 'cols_mobile',
                'selections' => array(
                    '1',
                    '2',
                    '3',
                    '4',
                    '5',
                    '6',
                    '7',
                    '8',
                    '9'
                ),
                'value' => '1',
            )
        );

        add_settings_field(
            'x_dist_text', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Horizontal gap in %', 'jub-portfolio-gallery')),
            array($this, 'field_text'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-layout', //section
            array(
                'id' => 'x_dist',
                'value' => '0',
                'validation_type' => 'required type="number" step="0.1"'
            )
        );

        add_settings_field(
            'y_dist_text', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Vertical gap in %', 'jub-portfolio-gallery')),
            array($this, 'field_text'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-layout', //section
            array(
                'id' => 'y_dist',
                'value' => '0',
                'validation_type' => 'required type="number" step="0.1"'
            )
        );

        add_settings_field(
            'img-size-dropdown', //id
            apply_filters($this->plugin_name . 'label-hover-class-dropdown', esc_html__('Image Size', 'jub-portfolio-gallery')), //title
            array($this, 'field_select'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-layout', //section
            array(
                'id' => 'image_size',
                'selections' => array(
                    'thumbnail',
                    'medium',
                    'large',
                    'full',
                ),
                'value' => 'full',
                'description' => esc_html__('The option "thumbnail" will generate equal sizes', 'jub-portfolio-gallery')
            )
        );

        add_settings_field(
            'display_title_below_checkbox', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Title below image', 'jub-portfolio-gallery')),
            array($this, 'field_checkbox'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-layout', //section
            array(
                'id' => 'display_title_below',
            )
        );

        add_settings_field(
            'display_excerpt_below_checkbox', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Excerpt below image', 'jub-portfolio-gallery')),
            array($this, 'field_checkbox'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-layout', //section
            array(
                'id' => 'display_excerpt_below',
            )
        );

        add_settings_field(
            'background-color-picker', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Image Background Color', 'jub-portfolio-gallery')), //title
            array($this, 'field_color_picker'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-layout', //section
            array(
                'description' => 'Set the Image background color (effect only visible if transparent images are used).',
                'id' => 'background_color',
                'value' => ''
            )
        );

        add_settings_field(
            'enable_lightbox_checkbox', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Enable Lightbox', 'jub-portfolio-gallery')),
            array($this, 'field_checkbox'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-layout', //section
            array(
                'description' => 'If enabled, a lightbox will be shown instead of the portfolio-post.',
                'id' => 'enable_lightbox',
            )
        );

        add_settings_field(
            'open_in_new_window_checkbox', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Open in new Window', 'jub-portfolio-gallery')),
            array($this, 'field_checkbox'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-layout', //section
            array(
                'description' => 'If enabled, the portfolio posts will be opened in a new window.',
                'id' => 'open_in_new_window',
            )
        );

        add_settings_field(
            'tags_text', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Included Tags', 'jub-portfolio-gallery')),
            array($this, 'field_text'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-layout', //section
            array(
                'description' =>  'Add the tag-id of items to display separated by comma. If empty, all tags will be shown. This option is useful if you want to display multiple portfolio grids with different content.',
                'id' => 'included_tags',
                'value' => '',


            )
        );

        //ROLLOVER-OPTIONS

        // add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
        add_settings_field(
            'hover-class-dropdown', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Hover Tranformation', 'jub-portfolio-gallery')), //title
            array($this, 'field_select'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-rollover', //section
            array(
                'id' => 'hover_class',
                'selections' => array(
                    '',
                    'hvr-grow',
                    'hvr-shrink',
                    'hvr-pulse',
                    'hvr-pulse-grow',
                    'hvr-pulse-shrink',
                    'hvr-push',
                    'hvr-pop',
                    'hvr-bounce-in',
                    'hvr-bounce-out',
                    'hvr-rotate',
                    'hvr-grow-rotate',
                    'hvr-float',
                    'hvr-sink',
                    'hvr-bob',
                    'hvr-hang',
                    'hvr-skew',
                    'hvr-skew-forward',
                    'hvr-skew-backward',
                    'hvr-wobble-vertical',
                    'hvr-wobble-horizontal',
                    'hvr-wobble-to-top-right',
                    'hvr-wobble-to-bottom-right',
                    'hvr-wobble-top',
                    'hvr-wobble-bottom',
                    'hvr-wobble-skew',
                    'hvr-buzz',
                    'hvr-buzz-out',
                    'hvr-forward',
                    'hvr-backward'
                ),
                'value' => '',
            )
        );


        add_settings_field(
            'hide-overlay-text-checkbox', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Hide Title', 'jub-portfolio-gallery')), //title
            array($this, 'field_checkbox'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-rollover', //section
            array(
                'id' => 'overlay_hide_text',
                'label' => 'Overlay-Text nicht anzeigen'
            )
        );

        add_settings_field(
            'hide-overlay-excerpt-checkbox', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Hide Post Excerpt', 'jub-portfolio-gallery')), //title
            array($this, 'field_checkbox'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-rollover', //section
            array(
                'id' => 'overlay_hide_excerpt',
                'label' => 'Overlay-Text nicht anzeigen',
                'value' => 0,

            )
        );

        add_settings_field(
            'overlay_animation_class_dropdown', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Overlay Animation', 'jub-portfolio-gallery')), //title
            array($this, 'field_select'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-rollover', //section
            array(
                'id' => 'overlay_animation_class',
                'selections' => array(
                    'fade',
                    'slide-left',
                    'slide-right',
                    'slide-top',
                    'slide-bottom',

                ),
                'value' => '',
            )
        );

        add_settings_field(
            'no_overlay_checkbox', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Disable Hover Overlay', 'jub-portfolio-gallery')),
            array($this, 'field_checkbox'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-rollover', //section
            array(
                'id' => 'no_overlay',
                'label' => 'Kein Overlay',
            )
        );

        add_settings_field(
            'overlay_initial_opacity_text',
            apply_filters($this->plugin_name . 'label', esc_html__('Initial Opacity', 'jub-portfolio-gallery')),
            array($this, 'field_text'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-rollover', //section
            array(
                'description' => esc_html__('Initial opacity of the overlay (always use commas/dots i.e 0.0 instead of 0)', 'jub-portfolio-gallery'),
                'id' => 'overlay_initial_opacity',
                'label' => '',
                'validation_type' => 'required type="number" step="0.1"'
            )
        );


        add_settings_field(
            'overlay_opacity_text',
            apply_filters($this->plugin_name . 'label', esc_html__('Hover Opacity', 'jub-portfolio-gallery')),
            array($this, 'field_text'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-rollover', //section
            array(
                'description' => esc_html__('Opacity of the overlay on hovering (always use commas/dots i.e 0.0 instead of 0)', 'jub-portfolio-gallery'),
                'id' => 'overlay_opacity',
                'label' => '',
                'validation_type' => 'required type="number" step="0.1"'
            )
        );

        add_settings_field(
            'overlay-initial-color-picker', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Initial Overlay Color', 'jub-portfolio-gallery')), //title
            array($this, 'field_color_picker'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-rollover', //section
            array(
                'description' => 'Overlay Farbe festlegen',
                'id' => 'overlay_initial_color',
                'value' => '#e67e22'
            )
        );

        add_settings_field(
            'overlay-color-picker', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Hover Overlay Color', 'jub-portfolio-gallery')), //title
            array($this, 'field_color_picker'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-rollover', //section
            array(
                'description' => 'Overlay Farbe festlegen',
                'id' => 'overlay_color',
                'value' => '#e67e22'
            )
        );

        add_settings_field(
            'overlay_initial_font_opacity_text',
            apply_filters($this->plugin_name . 'label', esc_html__('Initial Font Opacity', 'jub-portfolio-gallery')),
            array($this, 'field_text'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-rollover', //section
            array(
                'description' => esc_html__('Initial font-opacity (always use commas/dots i.e 0.0 instead of 0)', 'jub-portfolio-gallery'),
                'id' => 'overlay_initial_font_opacity',
                'label' => '',
                'validation_type' => 'required type="number" step="0.1"'
            )

        );


        add_settings_field(
            'overlay_font_opacity_text',
            apply_filters($this->plugin_name . 'label', esc_html__('Hover Font Opacity', 'jub-portfolio-gallery')),
            array($this, 'field_text'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-rollover', //section
            array(
                'description' => esc_html__('Opacity of the font on hovering (always use commas/dots i.e 0.0 instead of 0)', 'jub-portfolio-gallery'),
                'id' => 'overlay_font_opacity',
                'label' => '',
                'validation_type' => 'required type="number" step="0.1"'
            )
        );

        //Font size
        add_settings_field(
            'overlay_text_font_size', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Font Size', 'jub-portfolio-gallery')),
            array($this, 'field_text'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-rollover', //section
            array(
                'id' => 'overlay_font_size',
                'value' => '18',
                'validation_type' => 'required type="number" step="0.1"'
            )
        );


        add_settings_field(
            'overlay-text-color-picker', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Font Color', 'jub-portfolio-gallery')), //title
            array($this, 'field_color_picker'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-rollover', //section
            array(
                'id' => 'overlay_text_color',
                'class' => '',
                'value' => '#fff'
            )
        );

        //FILTER OPTIONS
        add_settings_field(
            'hide-filters-checkbox', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Hide Filter', 'jub-portfolio-gallery')), //title
            array($this, 'field_checkbox'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-filter', //section
            array(
                'id' => 'hide_filter',
                'label' => 'Filter nicht anzeigen'
            )
        );

        add_settings_field(
            'filter-button-color-picker', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Filter Color', 'jub-portfolio-gallery')), //title
            array($this, 'field_color_picker'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-filter', //section
            array(
                'id' => 'filter_button_color',
                'class' => '',
                'value' => '#000'
            )
        );

        add_settings_field(
            'filter-button-hover-color-picker', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Filter Hover Color', 'jub-portfolio-gallery')), //title
            array($this, 'field_color_picker'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-filter', //section
            array(
                'id' => 'filter_button_hover_color',
                'class' => '',
                'value' => '#34495e'
            )
        );

        add_settings_field(
            'filter-text-color-picker', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Filter Text-Color', 'jub-portfolio-gallery')), //title
            array($this, 'field_color_picker'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-filter', //section
            array(
                'id' => 'filter-button-text-color',
                'class' => '',
                'value' => '#fff'
            )
        );

        add_settings_field(
            'filter-text-hover-color-picker', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Filter Text Hover-Color', 'jub-portfolio-gallery')), //title
            array($this, 'field_color_picker'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-filter', //section
            array(
                'id' => 'filter-button-text-hover-color',
                'class' => '',
                'value' => ''
            )
        );

        add_settings_field(
            'filter-position-dropdown', //id
            apply_filters($this->plugin_name . 'label', esc_html__('Filter-Position', 'jub-portfolio-gallery')), //title
            array($this, 'field_select'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-filter', //section
            array(
                'id' => 'filter-position',
                'selections' => array(
                    'left',
                    'center',
                    'right'
                ),
                'value' => 'center',
            )
        );

        //Font size
        add_settings_field(
            'filter_all_button_text_font_size', //id
            apply_filters($this->plugin_name . 'label', esc_html__('All Button Text', 'jub-portfolio-gallery')),
            array($this, 'field_text'), //callback
            $this->plugin_name, //menu slug
            $this->plugin_name . '-filter', //section
            array(
                'id' => 'all_button_text',
                'value' => 'All',
            )
        );

        //get portfolio posts
        $post_args = array(
            'post_type' => 'jubportfolio',
            'orderby' => 'ID',
            'order' => 'ASC',
            'posts_per_page' => 100
        );

        $portfolios = get_posts($post_args);



        foreach ($portfolios as $key => $portfolio) {
            $id = $portfolio->ID;



            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($portfolio->ID), "full");

            add_settings_field(
                'item-cols-dropdown' . $id, //id
                apply_filters($this->plugin_name . 'label', esc_html__('Item: ' . $portfolio->post_title, 'jub-portfolio-gallery')), //title
                array($this, 'field_select'), //callback
                $this->plugin_name, //menu slug
                $this->plugin_name . '-grid', //section
                array(
                    'id' => 'item-col-width' . $id,
                    'selections' => array(
                        '1',
                        '2',
                        '3',
                        '4',
                        '5',
                        '6',
                        '7',
                        '8',
                        '9'
                    ),
                    'label' => esc_html__('Columns', 'jub-portfolio-gallery'),
                    'value' => '1',
                    'data' => json_encode(array($image_data, $id))
                )
            );

            add_settings_field(
                'item-image-background-dropdown' . $id, //id
                apply_filters($this->plugin_name . 'label', ''), //title
                array($this, 'field_color_picker'), //callback
                $this->plugin_name, //menu slug
                $this->plugin_name . '-grid', //section
                array(
                    'id' => 'item-background-color' . $id,
                    'description' => esc_html__('Background Color', 'jub-portfolio-gallery'),
                    'label' => esc_html__('Image Background Color', 'jub-portfolio-gallery')
                )
            );



            add_settings_field(
                'item-initial-overlay-colorpicker' . $id, //id
                apply_filters($this->plugin_name . 'label-hover-class-colorpicker', ''), //title
                array($this, 'field_color_picker'), //callback
                $this->plugin_name, //menu slug
                $this->plugin_name . '-grid', //section
                array(
                    'id' => 'item-initial-overlay-color' . $id,
                    'label' => esc_html__('Initial Overlay Color', 'jub-portfolio-gallery')
                )
            );


            add_settings_field(
                'item-overlay-colorpicker' . $id, //id
                apply_filters($this->plugin_name . 'label-hover-class-dropdown', ''), //title
                array($this, 'field_color_picker'), //callback
                $this->plugin_name, //menu slug
                $this->plugin_name . '-grid', //section
                array(
                    'id' => 'item-overlay-color' . $id,
                    'label' => esc_html__('Overlay Color', 'jub-portfolio-gallery')
                )
            );

            add_settings_field(
                'item-custom-link-dropdown' . $id, //id
                apply_filters($this->plugin_name . 'label-hover-class-dropdown', ''), //title
                array($this, 'field_text'), //callback
                $this->plugin_name, //menu slug
                $this->plugin_name . '-grid', //section
                array(
                    'id' => 'item-custom-link' . $id,
                    'label' => esc_html__('Custom Link', 'jub-portfolio-gallery'),
                    'description' => esc_html__(' Shows the portfolio item if no link provided', 'jub-portfolio-gallery')
                )
            );

        }
    }

    public function field_color_picker($args)
    {
        $defaults['class'] = '';
        $defaults['description'] = '';
        $defaults['label'] = '';
        $defaults['name'] = $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['placeholder'] = '';
        $defaults['type'] = 'text';
        $defaults['value'] = '';
        apply_filters($this->plugin_name . '-field-text-options-defaults', $defaults);

        $atts = wp_parse_args($args, $defaults);

        if (!empty($this->options[$atts['id']])) {
            $atts['value'] = $this->options[$atts['id']];
        }

        if (!empty($atts['label'])) {

            ?><label for="<?php echo esc_attr($atts['id']); ?>"><?php esc_html_e($atts['label'], 'jub-portfolio'); ?>
            : </label><?php

        }


        echo '<input id="' . esc_attr($atts['id']) . '" type="text" name="' . esc_attr($atts['name']) . '" value="' . esc_attr($atts['value']) . '" class="color-picker" >';
    }

    /**
     * Creates a text field
     *
     * @param    array $args The arguments for the field
     * @return    string The HTML field
     */
    public function field_text($args)
    {
        $defaults['class'] = '';
        $defaults['description'] = '';
        $defaults['label'] = '';
        $defaults['name'] = $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['placeholder'] = '';
        $defaults['type'] = 'text';
        $defaults['value'] = '';
        $validation_type = (isset($args['validation_type'])) ? $args['validation_type'] : '';

        apply_filters($this->plugin_name . '-field-text-options-defaults', $defaults);

        $atts = wp_parse_args($args, $defaults);

        if (!empty($this->options[$atts['id']])) {

            $atts['value'] = $this->options[$atts['id']];

        }

        include(plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-admin-field-text.php');
    }


    /**
     * Creates a select field
     *
     * Note: label is blank since its created in the Settings API
     *
     * @param    array $args The arguments for the field
     * @return    string                        The HTML field
     */
    public function field_select($args)
    {

        $defaults['aria'] = '';
        $defaults['blank'] = '';
        $defaults['class'] = '';
        $defaults['context'] = '';
        $defaults['description'] = '';
        $defaults['label'] = '';
        $defaults['name'] = $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['selections'] = array();
        $defaults['value'] = '';
        $defaults['data'] = '';

        apply_filters($this->plugin_name . '-field-select-options-defaults', $defaults);

        $atts = wp_parse_args($args, $defaults);

        if (!empty($this->options[$atts['id']])) {

            $atts['value'] = $this->options[$atts['id']];

        }

        if (empty($atts['aria']) && !empty($atts['description'])) {

            $atts['aria'] = $atts['description'];

        } elseif (empty($atts['aria']) && !empty($atts['label'])) {

            $atts['aria'] = $atts['label'];

        }


        include(plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-admin-field-select.php');
    }


    /**
     * Creates a checkbox field
     *
     * @param    array $args The arguments for the field
     * @return    string                        The HTML field
     */
    public function field_checkbox($args)
    {
        $defaults['class'] = '';
        $defaults['description'] = '';
        $defaults['label'] = '';
        $defaults['name'] = $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['value'] = "0";

        apply_filters($this->plugin_name . '-field-checkbox-options-defaults', $defaults);

        $atts = wp_parse_args($args, $defaults);

        if (!empty($this->options[$atts['id']])) {
            $atts['value'] = $this->options[$atts['id']];
        }

        include(plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-admin-field-checkbox.php');
    }
}

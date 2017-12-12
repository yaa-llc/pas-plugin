<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.huebscheseiten.de
 * @since      1.0.0
 *
 * @package    Jub_Portfolio_Gallery
 * @subpackage Jub_Portfolio_Gallery/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Jub_Portfolio_Gallery
 * @subpackage Jub_Portfolio_Gallery/public
 * @author     Jan Müller <jan.m@jungundbillig.de>
 */
class Jub_Portfolio_Gallery_Public
{
    private $plugin_name;

    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/jub-portfolio-gallery-public.css', array(), $this->version);

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_register_script('jub_lightbox', plugin_dir_url(__FILE__) . 'js/lightbox.min.js', array('jquery'), $this->version, true);

        wp_register_script('jub_images-loaded', plugin_dir_url(__FILE__) . 'js/images-loaded.js', array('jquery'), $this->version, true);

        wp_register_script('jub_masonry',  plugin_dir_url(__FILE__) . 'js/masonry.js', array ('jquery'), $this->version, true);

        wp_register_script('jub_main', plugin_dir_url(__FILE__) . 'js/jub-portfolio-gallery-public.js', array('jquery', 'jub_masonry', 'jub_images-loaded', 'jub_lightbox'), $this->version, true);

        //only enqueue minified
//        wp_register_script('jub_main', plugin_dir_url(__FILE__) . 'js/dist/all.js', array('jquery'), $this->version, true);
    }

    /**
     * Processes shortcode jub-portfolio
     *
     * @param   array $atts The attributes from the shortcode
     *
     * @return    mixed    $output        Output of the buffer
     */
    public function jubportfolio_shortcode($atts = array())
    {
        //enqueue scripts

        wp_enqueue_script('jub_main');

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-jub-portfolio-gallery-admin.php';
        $opts 		= array();
        $options = Jub_Portfolio_Gallery_Admin::get_options_list();
        foreach ( $options as $option ) {
            $opts[ $option[0] ] = $option[2];
        }


        $stored_default_options = get_option($this->plugin_name . '-options');

        $merged = wp_parse_args( $stored_default_options, $opts );

        $args = shortcode_atts($merged, $atts, 'jub-portfolio');

        $hide_filter = isset($args['hide_filter']) && $args['hide_filter'] == 1;

        $taxonomy = 'jubportfolio_category';

        ob_start();

        //Filter
        if (!$hide_filter) {
            include(plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-public-filter.php');
        }

        //Grid
        include(plugin_dir_path(__FILE__) . 'partials/' . $this->plugin_name . '-public-portfolio-grid.php');

        wp_reset_postdata();

        $output = ob_get_contents();

        ob_end_clean();

        return $output;
    }

    public function register_shortcodes()
    {
        add_shortcode('jub-portfolio', array($this, 'jubportfolio_shortcode'));
    }

    //Helpers
    function calcWidth($itemCols, $cols, $gutter)
    {
        $m = 0;
        if ($itemCols > 1) {
            $m = (($itemCols - 1) * $gutter);
        }

        return ((100 - (($cols - 1) * $gutter)) / $cols) * $itemCols + $m;
    }

    function applyItemOptions($generalOption, $itemOptionID, $args, &$result)
    {
        if (isset($args[$itemOptionID]) && $args[$itemOptionID] != '') {
            $result = $args[$itemOptionID];

        } else {
            $result = $generalOption;
        }
    }

    /* Convert hexdec color string to rgb(a) string */

    public function hex2rgba($color, $opacity = true) {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if(empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if($opacity){
            if(abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;
    }


}

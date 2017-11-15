<?php
/*
Plugin Name: Pacific Art Stone Custom Post Types
Description: Register custom post types for Pacific Art Stone website.
Version:     1
Author:      Yaa Otchere
Author URI:  http://yaaotchere.ca
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Our custom post type function
function create_posttype() {

    register_post_type( 'dealers',
        // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Dealers' ),
                'singular_name' => __( 'Dealer' ),
                'taxonomies'  => array( 'category' ),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'dealers'),
        )
    );

    register_post_type( 'altera_stone',
        // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Altera Stone' ),
                'singular_name' => __( 'Altera Stone' ),
                'taxonomies'  => array( 'category' ),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'altera'),
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );
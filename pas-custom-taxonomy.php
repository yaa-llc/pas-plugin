<?php
/*
Plugin Name: Pacific Art Stone Custom Taxonomy
Description: Register custom taxonomies for Pacific Art Stone website.
Version:     1
Author:      Yaa Otchere
Author URI:  http://yaaotchere.ca
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function stone_init() {
    // create a new taxonomy
    register_taxonomy(
        'stone',
        'post',
        array(
            'label' => __( 'Stone Type' ),
            'rewrite' => array( 'slug' => 'stone' ),
            'capabilities' => array(
                'assign_terms' => 'edit_guides',
                'edit_terms' => 'publish_guides'
            )
        )
    );
}
add_action( 'init', 'stone_init' );
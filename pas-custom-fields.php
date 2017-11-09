<?php
/*
Plugin Name: Pacific Art Stone Custom Fields
Description: Register custom fields for Pacific Art Stone website.
Version:     1
Author:      Yaa Otchere
Author URI:  http://yaaotchere.ca
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5a04737b386c5',
        'title' => 'Home Page Custom Fields',
        'fields' => array(
            array(
                'key' => 'field_5a0473a957b19',
                'label' => 'Home Page Hero Bar',
                'name' => 'home_page_hero_bar',
                'type' => 'flexible_content',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'layouts' => array(
                    '5a0473ba81aa0' => array(
                        'key' => '5a0473ba81aa0',
                        'name' => 'home_page_slider',
                        'label' => 'Home Page Slider',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_5a0473ed57b1a',
                                'label' => 'Slider Image and Text',
                                'name' => 'slider_image_and_text',
                                'type' => 'repeater',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'collapsed' => '',
                                'min' => 0,
                                'max' => 0,
                                'layout' => 'table',
                                'button_label' => '',
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_5a04751757b1b',
                                        'label' => 'Slider Image',
                                        'name' => 'slider_image',
                                        'type' => 'image',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'return_format' => 'array',
                                        'preview_size' => 'thumbnail',
                                        'library' => 'all',
                                        'min_width' => '',
                                        'min_height' => '',
                                        'min_size' => '',
                                        'max_width' => '',
                                        'max_height' => '',
                                        'max_size' => '',
                                        'mime_types' => '',
                                    ),
                                    array(
                                        'key' => 'field_5a04752b57b1c',
                                        'label' => 'Slider Headline',
                                        'name' => 'slider_headline',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'maxlength' => '',
                                    ),
                                    array(
                                        'key' => 'field_5a04757f57b1d',
                                        'label' => 'Slider Description',
                                        'name' => 'slider_description',
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'placeholder' => '',
                                        'prepend' => '',
                                        'append' => '',
                                        'maxlength' => '',
                                    ),
                                    array(
                                        'key' => 'field_5a04758957b1e',
                                        'label' => 'Link to Page',
                                        'name' => 'link_to_page',
                                        'type' => 'page_link',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '',
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'post_type' => array(
                                            0 => 'page',
                                        ),
                                        'taxonomy' => array(
                                        ),
                                        'allow_null' => 0,
                                        'allow_archives' => 1,
                                        'multiple' => 0,
                                    ),
                                ),
                            ),
                        ),
                        'min' => '',
                        'max' => '',
                    ),
                ),
                'button_label' => 'Add Row',
                'min' => '',
                'max' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => '7',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(
            0 => 'the_content',
        ),
        'active' => 1,
        'description' => '',
    ));

endif;
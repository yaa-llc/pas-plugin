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
                'label' => 'Home Page Content Blocks',
                'name' => 'home_page_content_blocks',
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
                    '5a047c47c88a1' => array(
                        'key' => '5a047c47c88a1',
                        'name' => 'home_page_intro_section',
                        'label' => 'Home Page Intro Section',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_5a047c84c88a2',
                                'label' => 'Home Page Introduction Paragraph',
                                'name' => 'home_page_introduction_paragraph',
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
                                'key' => 'field_5a047c92c88a3',
                                'label' => 'Home Page Secondary Introduction Paragraph',
                                'name' => 'home_page_secondary_introduction_paragraph',
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
                        ),
                        'min' => '',
                        'max' => '',
                    ),
                    '5a047cccc88a5' => array(
                        'key' => '5a047cccc88a5',
                        'name' => 'quick_links_section',
                        'label' => 'Quick Links Section',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_5a047cd4c88a6',
                                'label' => 'Quick Link',
                                'name' => 'quick_link',
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
                                        'key' => 'field_5a048077c88a7',
                                        'label' => 'Link',
                                        'name' => 'link',
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
                                ),
                            ),
                        ),
                        'min' => '',
                        'max' => '',
                    ),
                    '5a048442d2dbc' => array(
                        'key' => '5a048442d2dbc',
                        'name' => 'feature_collection',
                        'label' => 'Feature Collection',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_5a048459d2dbd',
                                'label' => 'Feature Collection Section Title',
                                'name' => 'feature_collection_section_title',
                                'type' => 'text',
                                'instructions' => 'Please write the section title here "Feature Collection"',
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
                                'key' => 'field_5a04847fd2dbe',
                                'label' => 'Stone Collection',
                                'name' => 'stone_collection',
                                'type' => 'post_object',
                                'instructions' => 'Please select the Stone Collection that is featured here',
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
                                'multiple' => 0,
                                'return_format' => 'object',
                                'ui' => 1,
                            ),
                            array(
                                'key' => 'field_5a0484dfd2dbf',
                                'label' => 'Feature Collection Description',
                                'name' => 'feature_collection_description',
                                'type' => 'text',
                                'instructions' => 'Please writ the description for this stone collection here',
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
                                'key' => 'field_5a048544d2dc0',
                                'label' => 'Feature Collection Background Image',
                                'name' => 'feature_collection_background_image',
                                'type' => 'image',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'return_format' => 'id',
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
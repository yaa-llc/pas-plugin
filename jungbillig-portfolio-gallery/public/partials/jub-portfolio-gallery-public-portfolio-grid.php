<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       www.huebscheseiten.de
 * @since      1.0.0
 *
 * @package    Jub_Portfolio_Gallery
 * @subpackage Jub_Portfolio_Gallery/public/partials
 */


?>
<div class="jbloader"></div>
<div class="jbgrid">

    <div class="grid-sizer"></div>
    <div class="gutter-sizer"></div>
    <?php


    //get portfolio posts
    $post_args = array(
        'post_type' => 'jubportfolio',
        'orderby' => 'ID',
        'order' => 'ASC',
        'posts_per_page' => 50000,

    );
    $tags = $args['included_tags'];
    if (!is_null($tags) && strlen($tags) > 0) {
        $tags_arr = explode(',', $tags);
        $tax = array(
            array(
                'taxonomy' => 'jubportfolio_category',
                'field' => 'id',
                'terms' => $tags_arr,                  // term id, term slug or term name
                'operator' => 'IN'
            )
        );

        $post_args['tax_query'] = $tax;
    } else {

    }


    $hover_overlay_classes = array();
    $portfolios = get_posts($post_args);


    $cols = $args['cols'];
    $cols_mobile = $args['cols_mobile'];
    $cols_tablet = $args['cols_tablet'];
    $gutter = $args['x_dist'];
    $margin_bottom = $args['y_dist'];
    //    $item_width = $this->calcWidth(1, $cols, $gutter);

    $hoverclass = $args['hover_class'];
    $background_color = $args['background_color'];
    $imageSize = $args['image_size'];
    $enable_lightbox = (isset($args['enable_lightbox']) && $args['enable_lightbox'] == 1);
    $open_in_new_window = (isset($args['open_in_new_window']) && $args['open_in_new_window'] == 1);

    $has_overlay = (!isset($args['no_overlay']) || $args['no_overlay'] == 0);
    $show_overlay_text = (!isset($args['overlay_hide_text']) || $args['overlay_hide_text'] == 0);
    $show_overlay_excerpt = (!isset($args['overlay_hide_excerpt']) ||  $args['overlay_hide_excerpt'] == 0);


    if ($has_overlay) {

        $overlay_initial_color = $args['overlay_initial_color'];
        $overlay_initial_opacity = $args['overlay_initial_opacity'];
        $overlay_initial_font_opacity = $args['overlay_initial_font_opacity'];
        $overlay_color = $args['overlay_color'];
        $overlay_opacity = $args['overlay_opacity'];
        $overlay_font_opacity = $args['overlay_font_opacity'];


        $overlay_font_size = $args['overlay_font_size'];
        $overlay_text_color = $args['overlay_text_color'];
        $overlay_animation_class = $args['overlay_animation_class'];
    }

    $displayTitleBelowImage = isset($args['display_title_below']) && $args['display_title_below'] == 1;
    $displayExcerptBelowImage = isset($args['display_excerpt_below']) && $args['display_excerpt_below'] == 1;

    $content = '';

    foreach ($portfolios as $portfolio) {
        $id = $portfolio->ID;

        $colOptionID = 'item-col-width' . $id;
        if (isset($args[$colOptionID])) {
            $colwidth = $args[$colOptionID];
        } else {
            $colwidth = 1;
        }

        $gridColClass = 'spec-grid-item' . $colwidth;

        $colorOptionID = 'item-background-color' . $id;
        $item_background_color = '';
        $this->applyItemOptions($background_color, $colorOptionID, $args, $item_background_color);

        $overlayInitialOptionID = 'item-initial-overlay-color' . $id;
        $item_overlay_initial_color = '';
        $this->applyItemOptions($overlay_initial_color, $overlayInitialOptionID, $args, $item_overlay_initial_color);

        //colors to rgba
        $item_overlay_initial_color = $this->hex2rgba($item_overlay_initial_color, $overlay_initial_opacity);

        $overlayOptionID = 'item-overlay-color' . $id;
        $item_overlay_color = '';
        $this->applyItemOptions($overlay_color, $overlayOptionID, $args, $item_overlay_color);
        $item_overlay_color = $this->hex2rgba($item_overlay_color, $overlay_opacity);
        $hover_overlay_class = 'hover-overlay-class' . $id;
        $hover_overlay_style = '.' . $hover_overlay_class . ':hover {background: ' . $item_overlay_color . '!important;}';
        array_push($hover_overlay_classes, $hover_overlay_style);


        if (!has_post_thumbnail($id)) {
            continue;
        }

        $thumbnail_url = get_the_post_thumbnail_url($id, $imageSize);

        $portfolio_url = get_post_permalink($id);
        $customLinkOptionID = 'item-custom-link' . $id;
        $link = '';

        if ($enable_lightbox) {
            $link = get_the_post_thumbnail_url($id, 'full');
        } else {
            $this->applyItemOptions($portfolio_url, $customLinkOptionID, $args, $link);
        }

        $new_window = '';
        if ($open_in_new_window) {
            $new_window = 'target="_blank"';
        }

        $cats = get_the_terms($id, $taxonomy);
        $categories = '';

        if (is_array($cats) && !is_wp_error($cats)) {
            foreach ($cats as $cat) {
                $categories .= $cat->name . ',';
            }
        }

        $categories = rtrim($categories, " ");

        if ($enable_lightbox) {
            $content .= '<a data-lightbox="image-1" data-title="' . $portfolio->post_title . '" href="' . $link . '"><div  data-category="' . $categories . '" class="grid-item ' . ' ' . $hoverclass . ' ' . $gridColClass . '">';
        } else {
            $content .= '<a '.$new_window.' href="' . $link . '"><div data-category="' . $categories . '" class="grid-item ' . $hoverclass . ' ' . $gridColClass . '">';
        }

        if ($has_overlay) {
            $title = ($show_overlay_text) ? '<span class="jb-title">' . $portfolio->post_title . '</span>' : '';
            $excerpt = ($show_overlay_excerpt) ? '<span class="jb-excerpt">' . $portfolio->post_excerpt . '</span>' : '';
            $content .= '<div  style="background:' . $item_overlay_initial_color . '; color: ' . $overlay_text_color . ';" class="overlay ' . $overlay_animation_class . ' ' . $hover_overlay_class . '">' . $title . $excerpt . '</div>';
        }

        $content .= '<img style="background:' . $item_background_color . ';" src="' . $thumbnail_url . '" alt="" />';

        if ($displayTitleBelowImage) {
            $content .= '<h3 class="portfolio-title">' . $portfolio->post_title . '</h3>';
        }

        if ($displayExcerptBelowImage) {
            $content .= '<div class="portfolio-excerpt">' . $portfolio->post_excerpt . '</div>';
        }

        $content .= '</div></a>';
    }

    echo $content;

    ?>
</div>


<style>
    .gutter-sizer {
        width: <?php echo($gutter) ?>%;
    }

    /*Mobile*/
    <?php
     for ($i = 1; $i < 1; $i++) {
     echo ('.spec-grid-item' . $i . ' {width:' . $this->calcWidth($i, $cols_mobile, $gutter) . '%;}');
     }

 ?>

    .grid-item, .grid-sizer {
        width: <?php echo($this->calcWidth(1, $cols_mobile, $gutter)) ?>%;
    }

    /*Tablets*/
    @media only screen and (min-width: 600px) {
    <?php
    for ($i = 1; $i < $cols_tablet; $i++) {
    echo ('.spec-grid-item' . $i . ' {width:' . $this->calcWidth($i, $cols_tablet, $gutter) . '%;}');
    }

?>

        .grid-item, .grid-sizer {
            width: <?php echo($this->calcWidth(1, $cols_tablet, $gutter)) ?>%;
        }

    }

    /*Desktop*/
    @media only screen and (min-width: 768px) {

    <?php
        for ($i = 1; $i < $cols; $i++) {
        echo ('.spec-grid-item' . $i . ' {width:' . $this->calcWidth($i, $cols, $gutter) . '% !important;}');
        }

?>

        .grid-item, .grid-sizer {
            width: <?php echo($this->calcWidth(1, $cols, $gutter)) ?>%;
        }
    }

    .grid-item {
        margin-bottom: <?php echo($margin_bottom) ?>%;
    }

    <?php
    for ($i = 0; $i < sizeof($hover_overlay_classes); $i++) {
    echo ($hover_overlay_classes[$i]);
    }

    ?>

    .grid-item .overlay .jb-title, .grid-item .overlay .jb-excerpt {
        opacity: <?php echo($overlay_initial_font_opacity)?>;
    }

    .grid-item .overlay:hover .jb-title, .grid-item .overlay:hover .jb-excerpt {
        opacity: <?php echo($overlay_font_opacity)?>;;

    }

    .grid-item .overlay:hover .jb-excerpt {
        transform: scale(1);
    }

    .overlay {
        font-size: <?php echo($overlay_font_size)?>px;
    }

    .jb-excerpt, .jb-title {
        transition: all 0.3s ease;
        -webkit-transition: all 0.3s ease;; /* Safari */
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
        padding: 10px;
    }

    .jb-excerpt {
        display: inline-block;
        font-size: <?php echo($overlay_font_size - 6)?>px;
        transform: scale(0.5);
        padding: 10px;
        transition-delay: 0.1s;
    }
</style>
<?php
$filter_button_color = $args['filter_button_color'];
$filter_button_hover_color = $args['filter_button_hover_color'];
$filter_button_text_color = $args['filter-button-text-color'];
$filter_button_text_hover_color = $args['filter-button-text-hover-color'];
$filter_position = $args['filter-position'];
$all_button_text = $args['all_button_text'];

echo '<div id="jub-portfolio-filter">';

echo '<a class="filter-button" href="#" id="all-items">'.$all_button_text.'</a>';

$tags = $args['included_tags'];
$tags_arr = null;
if (!is_null($tags) && strlen($tags) > 0) {
    $tags_arr = explode(',', $tags);
}

$all_categories = get_terms($taxonomy, array(
    'hide_empty' => true,
));

foreach ($all_categories as $cat) {
  
    if (!is_null($tags_arr)) {

        if (in_array($cat->term_id, $tags_arr)) {
            echo '<a class="filter-button" href="#" id="' . $cat->name . '">' . $cat->name . '</a>';
        }
    } else {
        echo '<a class="filter-button" href="#" id="' . $cat->name . '">' . $cat->name . '</a>';
    }


}
echo '</div>';

?>

<style>
    #jub-portfolio-filter {
        text-align: <?php echo($filter_position)?>;
    }

    #jub-portfolio-filter .filter-button {
        background-color: <?php echo($filter_button_color)?>;
        color: <?php echo($filter_button_text_color)?>;
    }

    #jub-portfolio-filter .filter-button:hover {
        background-color: <?php echo($filter_button_hover_color)?>;
        color: <?php echo($filter_button_text_hover_color)?>;
    }


</style>


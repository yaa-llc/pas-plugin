<?php


?>

<div class="no-items">
    <h1>You have to create at least one Portfolio Item with Featured Image.</h1>
</div>

<div class="jub-settings-wrapper">
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <input id='refresh-preview' type='button' class="button button-primary"
           value="<?php echo esc_html__('Show Preview', 'jub-portfolio-gallery') ?>">

    <input id='hide-preview' type='button' class="button button-primary"
           value="<?php echo esc_html__('Hide Preview', 'jub-portfolio-gallery') ?>">

    <div class="form-container">
        <form id="jub-gallery-form" method="post" action="options.php">
            <?php

            settings_fields($this->plugin_name . '-options');

            do_settings_sections($this->plugin_name);

            submit_button(esc_html__('Save as Default', 'jub-portfolio-gallery'));
            ?>
        </form>
    </div>

    <input id='generate-shortcode' type='button' class="button button-primary"
           value="<?php echo esc_html__('Generate Shortcode', 'jub-portfolio-gallery') ?>">
    <input id='shortcode_result' type="text">
    <input id='copy' type='button' class="button button-primary"
           value="<?php echo esc_html__('Copy to clipboard', 'jub-portfolio-gallery') ?>">


    <div class="grid-container">
        <div class="grid">
        </div>
    </div>


</div>

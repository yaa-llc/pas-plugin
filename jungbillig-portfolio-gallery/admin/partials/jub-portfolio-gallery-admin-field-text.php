<?php


if ( ! empty( $atts['label'] ) ) {

	?><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'jub-portfolio' ); ?>: </label><?php

}

?><input
	<?php echo  $validation_type?>
	class="<?php echo esc_attr( $atts['class'] ); ?>"
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['name'] ); ?>"
	placeholder="<?php echo esc_attr( $atts['placeholder'] ); ?>"
	type="<?php echo esc_attr( $atts['type'] ); ?>"
	value="<?php echo esc_attr( $atts['value'] ); ?>"
>
<?php
if ( ! empty( $atts['description'] ) ) {

	?><span class="description"><?php esc_html_e( $atts['description'], 'jub-portfolio' ); ?></span><?php

}
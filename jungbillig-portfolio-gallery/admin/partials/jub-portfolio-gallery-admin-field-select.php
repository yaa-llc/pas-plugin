<?php


if ( ! empty( $atts['label'] ) ) {

	?><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'test' ); ?>: </label><?php

}

?><select

	data-value="<?php echo esc_attr( $atts['data'] ); ?>"
	aria-label="<?php esc_attr( _e( $atts['aria'], 'jub-portfolio' ) ); ?>"
	class="<?php echo esc_attr( $atts['class'] ); ?>"
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['name'] ); ?>"><?php

if ( ! empty( $atts['blank'] ) ) {

	?><option value><?php esc_html_e( $atts['blank'], 'jub-portfolio' ); ?></option><?php

}

foreach ( $atts['selections'] as $selection ) {

	if ( is_array( $selection ) ) {

		$label = $selection['label'];
		$value = $selection['value'];

	} else {

		$label = strtolower( $selection );
		$value = strtolower( $selection );

	}

	?><option
		value="<?php echo esc_attr( $value ); ?>" <?php
		selected( $atts['value'], $value ); ?>><?php

		esc_html_e( $label, 'jub-portfolio' );

	?></option><?php

} // foreach

?></select>
<span class="description"><?php esc_html_e( $atts['description'], 'jub-portfolio' ); ?></span>
</label>

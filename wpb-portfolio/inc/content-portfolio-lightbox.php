<?php
	global $post;
	$id =  $post->ID;
	$external_url_btn_text = wpb_fp_get_option( 'wpb_fp_view_portfolio_btn_text_', 'wpb_fp_advanced', 'View Portfolio' );
	$view_portfolio_btn_target = wpb_fp_get_option( 'wpb_fp_view_portfolio_btn_target', 'wpb_fp_advanced', 'new' );
	$gallery_caption = wpb_fp_get_option( 'wpb_fp_gallery_caption', 'wpb_fp_gallery', 'on' );

	$btn_target = '';
	if( $view_portfolio_btn_target && $view_portfolio_btn_target == 'new' ){
		$btn_target = 'target="_blank"';
	}

	$quickview_content_type = get_post_meta( $id, 'wpb_fp_quickview_content_type', true );
	$video_iframe = get_post_meta( $id, 'wpb_fp_quickview_video_iframe', true );
	$feature_image = apply_filters( 'wpb_fp_quickview_feature_image', get_the_post_thumbnail( $id, 'full' ), $id );
	$caption = get_post(get_post_thumbnail_id())->post_excerpt;
	$images = get_post_meta( $id, 'wpb_fp_gallery', true );
	$gallery_feature_image = wpb_fp_get_option( 'wpb_fp_gallery_feature_image', 'wpb_fp_gallery', 'on' );

	if( $quickview_content_type && $quickview_content_type == 'video'){
		$quickview_content = $video_iframe;
	}else{
		$quickview_content = $feature_image;
	}
?> 



<div class="wpb_fp_row">
	<div class="wpb_fp_quick_view_img <?php echo ( $images ? 'wpb_fp_has_gallery' : 'wpb_fp_no_gallery' );?> <?php echo ( $gallery_feature_image == 'on' ? 'wpb_fp_gallery_has_feature_image' : 'wpb_fp_gallery_disable_feature_image' );?> <?php echo apply_filters( 'wpb_fp_quick_view_img_column', 'wpb_fp_col-md-6' )?> wpb_fp_col-sm-12">
		<?php echo $quickview_content; ?>
		<?php echo ( $caption && $gallery_caption == 'on' ? '<div class="wpb_fp_caption"><p>'.$caption.'</p></div>' : '' ); ?>
	</div>
	<div class="wpb_fp_quick_view_content <?php echo apply_filters( 'wpb_fp_quick_view_content_column', 'wpb_fp_col-md-6' )?> wpb_fp_col-sm-12">
		<h2><?php echo get_the_title( $id ); ?></h2>
		<?php 
			$content_post = get_post($id);
			$content = $content_post->post_content;
			$content = apply_filters( 'the_content', $content );
			$content = str_replace(']]>', ']]&gt;', $content);
			echo wpautop( $content );

			$wpb_fp_portfolio_ex_link = get_post_meta( $id, 'wpb_fp_portfolio_ex_link', true );
			if( isset($wpb_fp_portfolio_ex_link) && !empty($wpb_fp_portfolio_ex_link) ){
				echo '<a class="wpb_fp_btn" '.$btn_target.' href="'.$wpb_fp_portfolio_ex_link.'">'.$external_url_btn_text.'</a>';
			}
		?>

	</div>
</div>
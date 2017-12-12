<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Soliloquy_Lightbox_Shortcode{
    /**
     * Holds the class object.
     *
     * @since 2.3.0
     *
     * @var object
     */
    public static $instance;

    /**
     * Path to the file.
     *
     * @since 2.3.0
     *
     * @var string
     */
    public $file = __FILE__;

    /**
     * Holds the base class object.
     *
     * @since 2.3.0
     *
     * @var object
     */
    public $base;

    /**
     * Primary class constructor.
     *
     * @since 2.3.0
     */	
	function __construct(){
		
		$this->base = Soliloquy_Lightbox::get_instance();
		
    	add_action( 'soliloquy_before_output', array( $this, 'init' ) );
		add_filter( 'soliloquy_css', array( $this, 'lightbox_theme'), 10, 2 );
		
	}
	
	function init( $data ){
		
	    // If there is no lightbox, don't output anything.
	    $instance = Soliloquy_Shortcode::get_instance();
	    $key = ( wp_is_mobile() ? 'mobile_lightbox' : 'lightbox' );
	    if ( ! $instance->get_config( $key, $data ) ) {
	        return;
	    }

	    // Load the lightbox scripts, styles and theme.
	    $this->load( $data );

	    // Add lightbox contextual hooks and filters.
	    add_filter( 'soliloquy_output_item_data', array( $this, 'infuse' ), 10, 4 );
		add_filter( 'soliloquy_output_link_attr', array( $this, 'lightbox_attr' ), 10, 5 );
		add_action( 'soliloquy_api_slider', array ($this, 'lightbox_js' ) );
		add_action( 'soliloquy_api_on_load', array( $this, 'js_clone' ) );

	}
	
	/**
	 * Loads all of the necessary assets for the lightbox.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Data for the slider.
	 */
	function load( $data ) {
	
	    // Register and enqueue styles.
	    wp_register_style( $this->base->plugin_slug . '-style', plugins_url( 'assets/css/lightbox.css', $this->base->file ), array(), $this->base->version );
	    wp_enqueue_style( $this->base->plugin_slug . '-style' );
	
	    // Register and enqueue scripts.
	    wp_register_script( $this->base->plugin_slug . '-script', plugins_url( 'assets/js/min/lightbox-min.js', $this->base->file ), array( 'jquery' ), $this->base->version, true );
	    wp_enqueue_script( $this->base->plugin_slug . '-script' );
	
	    // Load the lightbox theme.
	    $instance = Soliloquy_Shortcode::get_instance();
	    $common = Soliloquy_Lightbox_Common::get_instance();

	    foreach ( (array) $common->lightbox_themes() as $array => $data ) {

	        if ( $instance->get_config( 'lightbox_theme', $data ) != $data['value'] || 'base' == $data['value'] ) {
	            continue;
	        }

	        wp_enqueue_style( $this->base->plugin_slug . $theme . '-theme', plugins_url( 'themes/' . $theme . '/style.css', $data['file'] ), array( $this->base->plugin_slug . '-style' ) );
	        break;
	    }
	
	}	
	/**
	 * Infuses a link to the image itself if the lightbox option is checked
	 * but no image link is found.
	 *
	 * @since 1.0.0
	 *
	 * @param array $item  Array of slide data.
	 * @param int $id      The current slider ID.
	 * @param array $data  Array of slider data.
	 * @param int $i       The current position in the slider.
	 * @return array $item Amended array of slide data.
	 */
	function infuse( $item, $id, $data, $i ) {
		
	    // If there is no lightbox, don't output anything.
	    $instance = Soliloquy_Shortcode::get_instance();
	    if ( ! $instance->get_config( 'lightbox', $data ) ) {
	        return $item;
	    }
	
	    // If the item has chosen not to enable the lightbox, pass over it.
	    if ( isset( $item['lightbox_enable'] ) && ! $item['lightbox_enable'] ) {
	        return $item;
	    }
	
	    // If no link is set and the user has not chosen to disable the lightbox, set it to the image itself.
	    if ( empty( $item['link'] ) ) {
	        if ( ! empty( $item['src'] ) ) {
	            $item['link'] = esc_url( $item['src'] );
	        } elseif ( isset( $item['thumb'] ) ) {
	            $item['link'] = esc_url( $item['thumb'] );
	        }
	    }
	    
	    // If no thumbnail has been set but thumbnails are active, make sure to generate the thumbnail.
	    if ( $instance->get_config( 'lightbox_thumbs', $data ) && empty( $item['lb_thumb'] ) ) {
		    // Get item id - attachments using the dynamic shortcode won't populate $item['id']
		    $itemID = ( isset( $item['id'] ) ? $item['id'] : $id );
		    
	        $item['lb_thumb'] = $instance->get_image_src( $itemID, $item, $data, 'lightbox' );
	    }
	
	    return apply_filters( 'soliloquy_lightbox_item_data', $item, $id, $data, $i );
	
	}
	
	/**
	 * Adds the proper attributes to images so they can be opened in the lightbox.
	 *
	 * @since 1.0.0
	 *
	 * @param string $attr  String of link attributes.
	 * @param int $id       The current slider ID.
	 * @param array $item   Array of slide data.
	 * @param array $data   Array of slider data.
	 * @param int $i        The current position in the slider.
	 * @return string $attr Amended string of link attributes.
	 */
	function lightbox_attr( $attr, $id, $item, $data, $i ) {
	
	    // If there is no lightbox, don't output anything.
	    $instance = Soliloquy_Shortcode::get_instance();
	    if ( ! $instance->get_config( 'lightbox', $data ) ) {
	        return $attr;
	    }
	
	    // If the item has chosen not to enable the lightbox, pass over it.
	    if ( isset( $item['lightbox_enable'] ) && ! $item['lightbox_enable'] ) {
	        return $attr;
	    }
	
	    // Add in the rel or data attribute for the lightbox.
	    $html5_attribute = ( ( $instance->get_config( 'lightbox_html5', $data ) == '1' ) ? 'data-soliloquybox-group' : 'rel' );
	    $attr .= ' ' . $html5_attribute . '="soliloquybox' . sanitize_html_class( $data['id'] ) . '"';
	
	    // If we have a title, add in the caption for the lightbox.
	    if ( ! empty( $item['caption'] ) ) {
	        $attr .= ' data-soliloquy-lightbox-caption="' . esc_html( do_shortcode( $item['caption'] ) ) . '"';
	    }
	
	    // If we have thumbnails, add in the thumbnail attribute as well.
	    if ( $instance->get_config( 'lightbox_thumbs', $data ) ) {
	        if ( ! empty( $item['lb_thumb'] ) ) {
	            $attr .= ' data-thumbnail="' . esc_url( $item['lb_thumb'] ) . '"';
	        }
	    }
	
	    // If a video, make the helper an iframe for third party videos, and html for local videos
	    if ( isset( $item['type'] ) && 'video' == $item['type'] ) {
	        $url   = $instance->get_video_data( $id, $item, $data, 'url' );
	        $vid_type = $instance->get_video_data( $id, $item, $data, 'type' );
	        switch ( $vid_type ) {
	            case 'local':
	                $attr .= ' data-soliloquybox-type="html" data-soliloquybox-href="' . esc_url( $url ) . '"';
	                break;
	
	            default:
	                $attr .= ' data-soliloquybox-type="iframe" data-soliloquybox-href="' . esc_url( $url ) . '"';
	                break;
	        }
	    }
	
	    // If an HTML slide, make the helper inline.
	    if ( isset( $item['type'] ) && 'html' == $item['type'] ) {
	        $attr .= ' data-soliloquybox-type="inline"';
	    }
	
	    return apply_filters( 'soliloquy_lightbox_attr', $attr, $id, $item, $data, $i );
	
	}
	
	/**
	 * Outputs the lightbox JS init code to initialize the lightbox.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Array of slider data.
	 */
	function lightbox_js( $data ) {
	
	    // If there is no lightbox, don't output anything.
	    $instance = Soliloquy_Shortcode::get_instance();
	    if ( ! $instance->get_config( 'lightbox', $data ) ) {
	        return;
	    }
	
	    ob_start();
	    ?>
	    // Unload video triggers inside the slider if videos should be loaded in lightbox only, otherwise the opposite.
	    <?php if ( $instance->get_config( 'lightbox_videos', $data ) ) : ?>
	    $(document).off('click.soliloquyYouTube<?php echo $data['id']; ?> click.soliloquyVimeo<?php echo $data['id']; ?> click.soliloquyWistia<?php echo $data['id']; ?> click.soliloquyLocal<?php echo $data['id']; ?>');
	    <?php endif; ?>
	
	    if ( typeof soliloquy_lightbox === 'undefined' || false === soliloquy_lightbox ) {
	        soliloquy_lightbox = {};
	    }
	
	    soliloquy_lightbox['<?php echo $data['id']; ?>'] = $('#soliloquy-<?php echo $data['id']; ?> .soliloquy-link').soliloquybox({
	        <?php do_action( 'soliloquy_lightbox_api_config_start', $data ); ?>
	        <?php if ( ! $instance->get_config( 'lightbox_keyboard', $data ) ) : ?>
	        keys: 0,
	        <?php endif; ?>
	        scrolling: 'no',
	        arrows: <?php echo $instance->get_config( 'lightbox_arrows', $data ); ?>,
	        aspectRatio: <?php echo $instance->get_config( 'lightbox_aspect', $data ); ?>,
	        loop: <?php echo $instance->get_config( 'lightbox_loop', $data ); ?>,
	        mouseWheel: <?php echo $instance->get_config( 'lightbox_mousewheel', $data ); ?>,
	        preload: 1,
	        nextEffect: '<?php echo $instance->get_config( 'lightbox_transition', $data ); ?>',
	        prevEffect: '<?php echo $instance->get_config( 'lightbox_transition', $data ); ?>',
	        tpl: {
	            wrap     : '<div class="soliloquybox-wrap" tabIndex="-1"><div class="soliloquybox-skin soliloquybox-theme-<?php echo $instance->get_config( 'lightbox_theme', $data ); ?>"><div class="soliloquybox-outer"><div class="soliloquybox-inner"></div></div></div></div>',
	            image    : '<img class="soliloquybox-image" src="{href}" alt="" />',
	            iframe   : '<iframe id="soliloquybox-frame{rnd}" name="soliloquybox-frame{rnd}" class="soliloquybox-iframe" frameborder="0" vspace="0" hspace="0" allowtransparency="true"\></iframe>',
	            error    : '<p class="soliloquybox-error"><?php echo esc_html__( 'The requested content cannot be loaded.<br/>Please try again later.</p>', 'soliloquy-lightbox' ); ?>',
	            closeBtn : '<a title="<?php echo esc_attr__( 'Close', 'soliloquy-lightbox' ); ?>" class="soliloquybox-item soliloquybox-close" href="javascript:;"></a>',
	            next     : '<a title="<?php echo esc_attr__( 'Next', 'soliloquy-lightbox' ); ?>" class="soliloquybox-nav soliloquybox-next" href="javascript:;"><span></span></a>',
	            prev     : '<a title="<?php echo esc_attr__( 'Previous', 'soliloquy-lightbox' ); ?>" class="soliloquybox-nav soliloquybox-prev" href="javascript:;"><span></span></a>'
	        },
	        helpers: {
	            <?php do_action( 'soliloquy_lightbox_api_helper_config', $data ); ?>
	            media: true,
	            title: {
	                <?php do_action( 'soliloquy_lightbox_api_title_config', $data ); ?>
	                type: '<?php echo $instance->get_config( 'lightbox_title', $data ); ?>'
	            },
	            video: {
	                autoplay: 1,
	                playpause: 1,
	                progress: 1,
	                current: 1,
	                duration: 1,
	                volume: 1
	            },
	            <?php if ( $instance->get_config( 'lightbox_thumbs', $data ) ) : ?>
	            thumbs: {
	                width: <?php echo $instance->get_config( 'lightbox_twidth', $data ); ?>,
	                height: <?php echo $instance->get_config( 'lightbox_theight', $data ); ?>,
	                source: function(current) {
	                    return $(current.element).data('thumbnail');
	                },
	                position: '<?php echo $instance->get_config( 'lightbox_tposition', $data ); ?>'
	            }
	            <?php endif; ?>
	        },
			<?php if ( $instance->get_config( 'lightbox_supersize', $data ) ) : ?>

			margin: 0,
			padding: 0,
			autoCenter: true,
			tpl: {
				wrap: '<div class="soliloquy-wrap soliloquy-supersize" tabIndex="-1"><div class="soliloquybox-skin"><div class="soliloquybox-outer"><div class="soliloquybox-inner"></div></div></div></div>'
			},
			<?php endif; ?>
	        
	        <?php do_action( 'soliloquy_lightbox_api_config_callback', $data ); ?>
	        beforeLoad: function(){
	            <?php if ( ! $instance->get_config( 'lightbox_videos', $data ) ) : ?>
	            if ( $(this.element).hasClass('soliloquy-video-link') && ! $.soliloquybox.isActive ) {
	                return false;
	            }
	            <?php endif; ?>
	
	            this.title = $(this.element).data('soliloquy-lightbox-caption');
	
	            <?php do_action( 'soliloquy_lightbox_api_before_load', $data ); ?>
	        },
	        afterLoad: function(){
		        
	            <?php if ( $instance->get_config( 'lightbox_supersize', $data ) ) : ?>
					
					$.extend(this, {
						width       : '100%',
						height      : '100%'
					});
				
				<?php endif; ?>
	    
	            <?php do_action( 'soliloquy_lightbox_api_after_load', $data ); ?>
	        },
	        beforeShow: function(){
	            $(window).on({
	                'resize.soliloquybox' : function(){
	                    $.soliloquybox.update();
	                }
	            });
	            soliloquy_slider['<?php echo $data['id']; ?>'].stopAuto();
	            <?php do_action( 'soliloquy_lightbox_api_before_show', $data ); ?>
	        },
	        afterShow: function(){
	            $('.soliloquybox-inner').swipe( {
	                swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
	                    if (direction === 'left') {
	                        $.soliloquybox.next(direction);
	                    } else if (direction === 'right') {
	                        $.soliloquybox.prev(direction);
	                    }
	                }
	            } );
	
	            <?php do_action( 'soliloquy_lightbox_api_after_show', $data ); ?>
	        },
	        beforeClose: function(){
	            <?php do_action( 'soliloquy_lightbox_api_before_close', $data ); ?>
	        },
	        afterClose: function(){
	            $(window).off('resize.soliloquybox');
	            <?php do_action( 'soliloquy_lightbox_api_after_close', $data ); ?>
	        },
	        onUpdate: function(){
	            <?php do_action( 'soliloquy_lightbox_api_on_update', $data ); ?>
	        },
	        onCancel: function(){
	            <?php do_action( 'soliloquy_lightbox_api_on_cancel', $data ); ?>
	        },
	        onPlayStart: function(){
	            <?php do_action( 'soliloquy_lightbox_api_on_play_start', $data ); ?>
	        },
	        onPlayEnd: function(){
	            <?php do_action( 'soliloquy_lightbox_api_on_play_end', $data ); ?>
	        }
	        <?php do_action( 'soliloquy_lightbox_api_config_end', $data ); ?>
	    });
	    <?php
	    echo ob_get_clean();
	
	}
	
	/**
	 * Removes lightbox attributes from cloned slides.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Array of slider data.
	 */
	function js_clone( $data ) {
	
	    // If there is no lightbox, don't output anything.
	    $instance = Soliloquy_Shortcode::get_instance();
	    if ( ! $instance->get_config( 'lightbox', $data ) ) {
	        return;
	    }
	
	    ob_start();
	    ?>
	    // Remove any rel attributes from cloned slides.
	    $('#soliloquy-container-<?php echo $data['id']; ?>').find('.soliloquy-clone > a').removeAttr('rel');
	    <?php
	    echo ob_get_clean();
	
	}	
	
	/**
	 * Adds a Lightbox Theme CSS file to the array of stylesheets to be loaded, if
	 * the given slider data's config specifies a non-base theme
	 *
	 * @since 2.2.3
	 *
	 * @param array $stylesheets Stylesheets
	 * @param array $data Slider Data
	 * @return array Stylesheets
	 */
	function lightbox_theme( $stylesheets, $data ) {
	
	    // Get instance
	    $instance = Soliloquy_Shortcode::get_instance();
	
	    // Get theme
	    $theme = $instance->get_config( 'lightbox_theme', $data );
		
	    // Check theme isn't base
	    if ( 'base' == $theme ) {
	        return $stylesheets;
	    }
	
	    // Add stylesheet to array for loading
	    $stylesheets[] = array(
	        'id'    => $this->base->plugin_slug . $theme . '-theme-style-css',
	        'href'  => esc_url( add_query_arg( 'ver', $this->base->version, plugins_url( 'themes/' . $theme . '/style.css', $this->base->file ) ) ),
	    );

	    // Return
	    return $stylesheets;
	
	}
		
    /**
     * Returns the singleton instance of the class.
     *
     * @since 2.3.0
     *
     * @return object The Soliloquy_Thumbnails_Shortcode object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Soliloquy_Lightbox_Shortcode ) ) {
            self::$instance = new Soliloquy_Lightbox_Shortcode();
        }

        return self::$instance;

    }
}

//Load the Shortcode Class
$soliloquy_lightbox_shortcode = Soliloquy_Lightbox_Shortcode::get_instance();
<?php
	
class Soliloquy_Lightbox_Mextaboxes{

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
    public function __construct() {

    	// Get base instance
    	$this->base = Soliloquy_Lightbox::get_instance();
    	
		add_filter( 'soliloquy_defaults', array( $this, 'defaults' ), 10, 2 );
		add_filter( 'soliloquy_meta_defaults', array( $this, 'meta_defaults' ), 10, 3 );
		add_filter( 'soliloquy_tab_nav', array( $this, 'tab_nav' ) );
		add_action( 'soliloquy_tab_lightbox', array( $this, 'lightbox_tab' ) );
		add_action( 'soliloquy_mobile_box', array( $this, 'tab_mobile' ) );
		
		//Theses will be deprciated in later versions
		add_action( 'soliloquy_after_image_meta_settings', array( $this, 'meta' ), 10, 3 );
		add_action( 'soliloquy_after_video_meta_settings', array( $this, 'meta' ), 10, 3 );
		
		add_filter( 'soliloquy_ajax_save_meta', array( $this, 'save_meta' ), 10, 4 );
		add_filter( 'soliloquy_ajax_save_bulk_meta', array( $this, 'save_meta' ), 10, 4 );
		
    	add_filter( 'soliloquy_save_settings', array( $this, 'save' ), 10, 2 );
		add_action( 'soliloquy_saved_settings', array( $this, 'crop' ), 10, 3 );

    	//Backbone Models
		add_action( 'soliloquy_metabox_scripts', array( $this, 'scripts' ) );
		add_action( 'soliloquy_print_templates', array( $this, 'meta_settings' ) , 10, 3 );
    		
	}
	
	/**
	 * Loads scripts for our metaboxes.
	 *
	 * @since 2.3.0
	 */
	function scripts() {
	    
	     wp_enqueue_script( $this->base->plugin_slug . '-media', plugins_url( 'assets/js/media-edit.js', $this->base->file ), array( 'jquery' ), $this->base->version , true );
	
	
	}	
	
	/**
	 * Applies a default to the addon setting.
	 *
	 * @since 1.0.0
	 *
	 * @param array $defaults  Array of default config values.
	 * @param int $post_id     The current post ID.
	 * @return array $defaults Amended array of default config values.
	 */
	function defaults( $defaults, $post_id ) {
	
	    $defaults['lightbox']            = 0;
	    $defaults['lightbox_theme']      = 'base';
	    $defaults['lightbox_title']      = 'inside';
	    $defaults['lightbox_arrows']     = 1;
	    $defaults['lightbox_keyboard']   = 1;
	    $defaults['lightbox_mousewheel'] = 0;
	    $defaults['lightbox_aspect']     = 1;
	    $defaults['lightbox_loop']       = 1;
	    $defaults['lightbox_transition'] = 'fade';
	    $defaults['lightbox_html5']      = 0;
	    $defaults['lightbox_videos']     = 1;
	    $defaults['lightbox_thumbs']     = 1;
	    $defaults['lightbox_twidth']     = 75;
	    $defaults['lightbox_theight']    = 50;
	    $defaults['lightbox_tposition']  = 'bottom';
	    $defaults['lightbox_supersize']  = 0;
	
	    // Mobile
	    $defaults['mobile_lightbox']     = 0;
	    return $defaults;
	
	}
	
	/**
	 * Applies defaults to attachment meta settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $defaults  Array of default config values.
	 * @param int $post_id     The current post ID.
	 * @param int $attach_ud   The current attachment ID.
	 * @return array $defaults Amended array of default config values.
	 */
	function meta_defaults( $defaults, $post_id, $attach_id ) {
	
	    $defaults['lightbox_enable'] = 1;
	    return $defaults;
	
	}

	/**
	 * Filters in a new tab for the addon.
	 *
	 * @since 1.0.0
	 *
	 * @param array $tabs  Array of default tab values.
	 * @return array $tabs Amended array of default tab values.
	 */
	function tab_nav( $tabs ) {
	
	    $tabs['lightbox'] = esc_attr__( 'Lightbox', 'soliloquy-lightbox' );
	    return $tabs;
	
	}
	
	function tab_mobile( $post ) {
	
	    $instance = Soliloquy_Metaboxes::get_instance();
	    ?>
	    <tr id="soliloquy-config-mobile-lightbox-box">
	        <th scope="row">
	            <label for="soliloquy-config-mobile-lightbox"><?php esc_html_e( 'Enable Lightbox on Mobile?', 'soliloquy-lightbox' ); ?></label>
	        </th>
	        <td>
	            <input id="soliloquy-config-mobile-lightbox" type="checkbox" name="_soliloquy[mobile_lightbox]" value="<?php echo $instance->get_config( 'mobile_lightbox', $instance->get_config_default( 'mobile_lightbox' ) ); ?>" <?php checked( $instance->get_config( 'mobile_lightbox', $instance->get_config_default( 'mobile_lightbox' ) ), 1 ); ?> />
	            <span class="description"><?php esc_html_e( 'Enables or disables the slider lightbox on mobile devices.', 'soliloquy-lightbox' ); ?></span>
	        </td>
	    </tr>
	    <?php
	
	}	
	/**
	 * Callback for displaying the UI for setting lightbox options.
	 *
	 * @since 1.0.0
	 *
	 * @param object $post The current post object.
	 */
	function lightbox_tab( $post ) {
	
	    $instance = Soliloquy_Metaboxes::get_instance();
	    $common = Soliloquy_Lightbox_Common::get_instance();
	    ?>
	    <div id="soliloquy-lightbox">
		    <div class="soliloquy-config-header">
	        	<h2 class="soliloquy-intro"><?php esc_html_e(  'The settings below adjust the lightbox settings for the slider.', 'soliloquy-lightbox' ); ?></h2>
				<p class="soliloquy-help"><?php esc_html_e(  'Need some help?', 'soliloquy' ); ?><a href="http://soliloquywp.com/docs/lightbox-addon/" target="_blank"><?php esc_html_e(  ' Watch a video on how to setup your slider configuration', 'soliloquy-lightbox' ); ?></a></p>
			</div>	    
	        <table class="form-table">
	            <tbody>
		            
	                <tr id="soliloquy-config-lightbox-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox"><?php esc_html_e( 'Enable Lightbox?', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
	                        <input id="soliloquy-config-lightbox" type="checkbox" name="_soliloquy[lightbox]" value="<?php echo $instance->get_config( 'lightbox', $instance->get_config_default( 'lightbox' ) ); ?>" <?php checked( $instance->get_config( 'lightbox', $instance->get_config_default( 'lightbox' ) ), 1 ); ?> data-conditional="soliloquy-config-lightbox-theme-box,soliloquy-config-lightbox-title-display-box,soliloquy-config-lightbox-arrows-box,soliloquy-config-lightbox-keyboard-box,soliloquy-config-lightbox-mousewheel-box,soliloquy-config-lightbox-aspect-box,soliloquy-config-lightbox-loop-box,soliloquy-config-lightbox-effect-box,soliloquy-config-lightbox-html5-box,soliloquy-config-lightbox-videos-box,soliloquy-config-lightbox-thumbnails-box,soliloquy-config-lightbox-thumbnails-width-box,soliloquy-config-lightbox-thumbnails-height-box,soliloquy-config-lightbox-thumbnails-position-box,soliloquy-config-supersize-box,soliloquy-config-lightbox-pinterest-box,soliloquy-config-lightbox-pinterest-position-box,soliloquy-config-lightbox-pinterest-color-box" />
	                        <span class="description"><?php esc_html_e(  'Enables or disables the slider lightbox.', 'soliloquy-lightbox' ); ?></span>
	                    </td>
	                </tr>
	                <tr id="soliloquy-config-lightbox-theme-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox-theme"><?php esc_html_e(  'Lightbox Theme', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
		                    <div class="soliloquy-select">
	                        <select id="soliloquy-config-lightbox-theme" name="_soliloquy[lightbox_theme]" class="soliloquy-chosen" data-soliloquy-chosen-options='{ "disable_search":"true", "width": "100%" }'>
	                            <?php foreach ( (array) $common->lightbox_themes() as $i => $data ) : ?>
	                                <option value="<?php echo $data['value']; ?>"<?php selected( $data['value'], $instance->get_config( 'lightbox_theme', $instance->get_config_default( 'lightbox_theme' ) ) ); ?>><?php echo $data['name']; ?></option>
	                            <?php endforeach; ?>
	                        </select>
		                    </div>
	                        <p class="description"><?php esc_html_e(  'Sets the theme for the lightbox display.', 'soliloquy-lightbox' ); ?></p>
	                    </td>
	                </tr>
			        <tr id="soliloquy-config-supersize-box">
			            <th scope="row">
			                <label for="soliloquy-config-supersize"><?php _e( 'Enable Lightbox Supersize?', 'soliloquy-supersize' ); ?></label>
			            </th>
			            <td>
			                <input id="soliloquy-config-supersize" type="checkbox" name="_soliloquy[lightbox_supersize]" value="<?php echo $instance->get_config( 'supersize', $instance->get_config_default( 'lightbox_supersize' ) ); ?>" <?php checked( $instance->get_config( 'lightbox_supersize', $instance->get_config_default( 'lightbox_supersize' ) ), 1 ); ?> />
			                <span class="description"><?php _e( 'Enables or disables supersize mode for the lightbox.', 'soliloquy-supersize' ); ?></span>
			            </td>
			        </tr>	  	                
	                <tr id="soliloquy-config-lightbox-title-display-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox-title-display"><?php esc_html_e(  'Lightbox Caption Position', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
		                    <div class="soliloquy-select">
	                        <select id="soliloquy-config-lightbox-title-display" name="_soliloquy[lightbox_title]" class="soliloquy-chosen" data-soliloquy-chosen-options='{ "disable_search":"true", "width": "100%" }'>
	                            <?php foreach ( (array) $common->lightbox_titles() as $i => $data ) : ?>
	                                <option value="<?php echo $data['value']; ?>"<?php selected( $data['value'], $instance->get_config( 'lightbox_title', $instance->get_config_default( 'lightbox_title' ) ) ); ?>><?php echo $data['name']; ?></option>
	                            <?php endforeach; ?>
	                        </select>
		                    </div>
	                        <p class="description"><?php esc_html_e(  'Sets the display of the captions inside of the lightbox.', 'soliloquy-lightbox' ); ?></p>
	                    </td>
	                </tr>
	                <tr id="soliloquy-config-lightbox-videos-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox-videos"><?php esc_html_e( 'Load Videos in Lightbox?', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
	                        <input id="soliloquy-config-lightbox-videos" type="checkbox" name="_soliloquy[lightbox_videos]" value="<?php echo $instance->get_config( 'lightbox_videos', $instance->get_config_default( 'lightbox_videos' ) ); ?>" <?php checked( $instance->get_config( 'lightbox_videos', $instance->get_config_default( 'lightbox_videos' ) ), 1 ); ?> />
	                        <span class="description"><?php esc_html_e( 'Loads video slides in the lightbox on click instead of inside the slider itself.', 'soliloquy-lightbox' ); ?></span>
	                    </td>
	                </tr>
	                <tr id="soliloquy-config-lightbox-arrows-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox-arrows"><?php esc_html_e(  'Enable Lightbox Arrows?', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
	                        <input id="soliloquy-config-lightbox-arrows" type="checkbox" name="_soliloquy[lightbox_arrows]" value="<?php echo $instance->get_config( 'lightbox_arrows', $instance->get_config_default( 'lightbox_arrows' ) ); ?>" <?php checked( $instance->get_config( 'lightbox_arrows', $instance->get_config_default( 'lightbox_arrows' ) ), 1 ); ?> />
	                        <span class="description"><?php esc_html_e(  'Enables or disables the lightbox navigation arrows.', 'soliloquy-lightbox' ); ?></span>
	                    </td>
	                </tr>
	                <tr id="soliloquy-config-lightbox-keyboard-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox-keyboard"><?php esc_html_e(  'Enable Keyboard Navigation?', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
	                        <input id="soliloquy-config-lightbox-keyboard" type="checkbox" name="_soliloquy[lightbox_keyboard]" value="<?php echo $instance->get_config( 'lightbox_keyboard', $instance->get_config_default( 'lightbox_keyboard' ) ); ?>" <?php checked( $instance->get_config( 'lightbox_keyboard', $instance->get_config_default( 'lightbox_keyboard' ) ), 1 ); ?> />
	                        <span class="description"><?php esc_html_e(  'Enables or disables keyboard navigation in the lightbox.', 'soliloquy-lightbox' ); ?></span>
	                    </td>
	                </tr>
	                <tr id="soliloquy-config-lightbox-mousewheel-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox-mousewheel"><?php esc_html_e(  'Enable Mousewheel Navigation?', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
	                        <input id="soliloquy-config-lightbox-mousewheel" type="checkbox" name="_soliloquy[lightbox_mousewheel]" value="<?php echo $instance->get_config( 'lightbox_mousewheel', $instance->get_config_default( 'lightbox_mousewheel' ) ); ?>" <?php checked( $instance->get_config( 'lightbox_mousewheel', $instance->get_config_default( 'lightbox_mousewheel' ) ), 1 ); ?> />
	                        <span class="description"><?php esc_html_e(  'Enables or disables mousewheel navigation in the lightbox.', 'soliloquy-lightbox' ); ?></span>
	                    </td>
	                </tr>
	                <tr id="soliloquy-config-lightbox-loop-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox-loop"><?php esc_html_e(  'Loop Lightbox Navigation?', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
	                        <input id="soliloquy-config-lightbox-loop" type="checkbox" name="_soliloquy[lightbox_loop]" value="<?php echo $instance->get_config( 'lightbox_loop', $instance->get_config_default( 'lightbox_loop' ) ); ?>" <?php checked( $instance->get_config( 'lightbox_loop', $instance->get_config_default( 'lightbox_loop' ) ), 1 ); ?> />
	                        <span class="description"><?php esc_html_e( 'Enables or disables infinite navigation cycling of the lightbox.', 'soliloquy-lightbox' ); ?></span>
	                    </td>
	                </tr>
	                <tr id="soliloquy-config-lightbox-effect-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox-effect"><?php esc_html_e( 'Lightbox Transition Effect', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
		                    <div class="soliloquy-select">
	                        <select id="soliloquy-config-lightbox-effect" name="_soliloquy[lightbox_transition]" class="soliloquy-chosen" data-soliloquy-chosen-options='{ "disable_search":"true", "width": "100%" }'>
	                            <?php foreach ( (array) $common->lightbox_transition_effects() as $i => $data ) : ?>
	                                <option value="<?php echo $data['value']; ?>"<?php selected( $data['value'], $instance->get_config( 'lightbox_transition', $instance->get_config_default( 'lightbox_transition' ) ) ); ?>><?php echo $data['name']; ?></option>
	                            <?php endforeach; ?>
	                        </select>
		                    </div>
	                        <p class="description"><?php esc_html_e( 'Type of transition between images in the lightbox view.', 'soliloquy-lightbox' ); ?></p>
	                    </td>
	                </tr>	             
	                <tr id="soliloquy-config-lightbox-aspect-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox-aspect"><?php esc_html_e(  'Keep Aspect Ratio?', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
	                        <input id="soliloquy-config-lightbox-aspect" type="checkbox" name="_soliloquy[lightbox_aspect]" value="<?php echo $instance->get_config( 'lightbox_aspect', $instance->get_config_default( 'lightbox_aspect' ) ); ?>" <?php checked( $instance->get_config( 'lightbox_aspect', $instance->get_config_default( 'lightbox_aspect' ) ), 1 ); ?> />
	                        <span class="description"><?php esc_html_e( 'If enabled, images will always resize based on the original aspect ratio.', 'soliloquy-lightbox' ); ?></span>
	                    </td>
	                </tr>
	                <tr id="soliloquy-config-lightbox-thumbnails-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox-thumbnails"><?php esc_html_e( 'Enable Lightbox Thumbnails?', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
	                        <input id="soliloquy-config-lightbox-thumbnails" type="checkbox" name="_soliloquy[lightbox_thumbs]" value="<?php echo $instance->get_config( 'lightbox_thumbs', $instance->get_config_default( 'lightbox_thumbs' ) ); ?>" <?php checked( $instance->get_config( 'lightbox_thumbs', $instance->get_config_default( 'lightbox_thumbs' ) ), 1 ); ?> />
	                        <span class="description"><?php esc_html_e( 'Enables or disables lightbox thumbnails.', 'soliloquy-lightbox' ); ?></span>
	                    </td>
	                </tr>
	                <tr id="soliloquy-config-lightbox-thumbnails-width-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox-thumbnails-width"><?php esc_html_e( 'Lightbox Thumbnails Width', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
	                        <input id="soliloquy-config-lightbox-thumbnails-width" type="number" name="_soliloquy[lightbox_twidth]" value="<?php echo $instance->get_config( 'lightbox_twidth', $instance->get_config_default( 'lightbox_twidth' ) ); ?>" /> <span class="soliloquy-unit"><?php esc_html_e( 'px', 'soliloquy-lightbox' ); ?></span>
	                        <p class="description"><?php esc_html_e( 'Sets the width of each lightbox thumbnail.', 'soliloquy-lightbox' ); ?></p>
	                    </td>
	                </tr>
	                <tr id="soliloquy-config-lightbox-thumbnails-height-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox-thumbnails-height"><?php esc_html_e( 'Lightbox Thumbnails Height', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
	                        <input id="soliloquy-config-lightbox-thumbnails-height" type="number" name="_soliloquy[lightbox_theight]" value="<?php echo $instance->get_config( 'lightbox_theight', $instance->get_config_default( 'lightbox_theight' ) ); ?>" /> <span class="soliloquy-unit"><?php esc_html_e( 'px', 'soliloquy-lightbox' ); ?></span>
	                        <p class="description"><?php esc_html_e( 'Sets the height of each lightbox thumbnail.', 'soliloquy-lightbox' ); ?></p>
	                    </td>
	                </tr>
	                <tr id="soliloquy-config-lightbox-thumbnails-position-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox-thumbnails-position"><?php esc_html_e( 'Lightbox Thumbnails Position', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
		                    <div class="soliloquy-select">
	                        <select id="soliloquy-config-lightbox-thumbnails-position" name="_soliloquy[lightbox_tposition]" class="soliloquy-chosen" data-soliloquy-chosen-options='{ "disable_search":"true", "width": "100%" }'>
	                            <?php foreach ( (array) $common->lightbox_thumbnail_positions() as $i => $data ) : ?>
	                                <option value="<?php echo $data['value']; ?>"<?php selected( $data['value'], $instance->get_config( 'lightbox_tposition', $instance->get_config_default( 'lightbox_tposition' ) ) ); ?>><?php echo $data['name']; ?></option>
	                            <?php endforeach; ?>
	                        </select>
		                    </div>
	                        <p class="description"><?php esc_html_e( 'Sets the position of the lightbox thumbnails.', 'soliloquy-lightbox' ); ?></p>
	                    </td>
	                </tr>
	                <tr id="soliloquy-config-lightbox-html5-box">
	                    <th scope="row">
	                        <label for="soliloquy-config-lightbox-html5"><?php esc_html_e( 'HTML5 Output?', 'soliloquy-lightbox' ); ?></label>
	                    </th>
	                    <td>
	                        <input id="soliloquy-config-lightbox-html5" type="checkbox" name="_soliloquy[lightbox_html5]" value="1" <?php checked( $instance->get_config( 'lightbox_html5', $instance->get_config_default( 'lightbox_html5' ) ), 1 ); ?> />
	                        <span class="description"><?php esc_html_e( 'If enabled, uses data-soliloquy-lightbox instead of rel attributes for W3C HTML5 validation.', 'soliloquy-lightbox' ); ?></span>
	                    </td>
	                </tr>
			                      
	                <?php do_action( 'soliloquy_lightbox_box', $post ); ?>
	            </tbody>
	        </table>
	    </div>
	    <?php
	
	}
	
	/**
	 * Outputs the lightbox meta fields.
	 *
	 * @since 1.0.0
	 *
	 * @param int $attach_id The current attachment ID.
	 * @param array $data    Array of attachment data.
	 * @param int $post_id   The current post ID.
	 */
	function meta( $attach_id, $data, $post_id ) {
	
	    $instance = Soliloquy_Metaboxes::get_instance();
	    ?>
	    <label class="setting">
	        <span class="name"><?php esc_html_e( 'Load in Lightbox?', 'soliloquy-lightbox' ); ?></span>
			<input id="soliloquy-lightbox-enable-<?php echo $attach_id; ?>" class="soliloquy-lightbox-enable" type="checkbox" name="_soliloquy[lightbox_enable]" data-soliloquy-meta="lightbox_enable" value="<?php echo $instance->get_meta( 'lightbox_enable', $attach_id, $instance->get_meta_default( 'lightbox_enable', $attach_id ) ); ?>"<?php checked( $instance->get_meta( 'lightbox_enable', $attach_id, $instance->get_meta_default( 'lightbox_enable', $attach_id ) ), 1 ); ?> />
		</label>
	    <?php
	
	}	
	/**
	 * Callback for displaying the UI for setting mobile lightbox options.
	 *
	 * @since 1.0.0
	 *
	 * @param object $post The current post object.
	 */
	
	
	function meta_settings(){
        // Soliloquy Meta Editor
        // Use: wp.media.template( 'soliloquy-meta-editor-lightbox' )
        ?>
        <script type="text/html" id="tmpl-soliloquy-meta-editor-lightbox">
		
			<div class="soliloquy-meta">
					        
			    <label class="setting">
			        <span class="name"><?php esc_html_e( 'Load in Lightbox?', 'soliloquy-lightbox' ); ?></span>
					<input class="soliloquy-lightbox-enable" type="checkbox" name="lightbox_enable"  value="1"<# if ( data.lightbox_enable == '1' ) { #> checked <# } #> />
					<span class="check-label"><?php esc_html_e( 'Opens slide in lightbox, requires Lightbox to be enabled within the Lightbox tab.', 'soliloquy-lightbox' ) ?></span>

				</label>
				        
	        </div>
        
        </script>
        <?php	
	}	
	
	/**
	 * Saves the addon settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings  Array of settings to be saved.
	 * @param int $post_id     The current post ID.
	 * @return array $settings Amended array of settings to be saved.
	 */
	function save( $settings, $post_id ) {
	
	    $settings['config']['lightbox']            = isset( $_POST['_soliloquy']['lightbox'] ) ? 1 : 0;
	    $settings['config']['lightbox_theme']      = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_soliloquy']['lightbox_theme'] );
	    $settings['config']['lightbox_title']      = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_soliloquy']['lightbox_title'] );
	    $settings['config']['lightbox_arrows']     = isset( $_POST['_soliloquy']['lightbox_arrows'] ) ? 1 : 0;
	    $settings['config']['lightbox_keyboard']   = isset( $_POST['_soliloquy']['lightbox_keyboard'] ) ? 1 : 0;
	    $settings['config']['lightbox_mousewheel'] = isset( $_POST['_soliloquy']['lightbox_mousewheel'] ) ? 1 : 0;
	    $settings['config']['lightbox_aspect']     = isset( $_POST['_soliloquy']['lightbox_aspect'] ) ? 1 : 0;
	    $settings['config']['lightbox_loop']       = isset( $_POST['_soliloquy']['lightbox_loop'] ) ? 1 : 0;
	    $settings['config']['lightbox_transition'] = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_soliloquy']['lightbox_transition'] );
	    $settings['config']['lightbox_html5']      = isset( $_POST['_soliloquy']['lightbox_html5'] ) ? 1 : 0;
	    $settings['config']['lightbox_videos']     = isset( $_POST['_soliloquy']['lightbox_videos'] ) ? 1 : 0;
	    $settings['config']['lightbox_thumbs']     = isset( $_POST['_soliloquy']['lightbox_thumbs'] ) ? 1 : 0;
	    $settings['config']['lightbox_twidth']     = absint( $_POST['_soliloquy']['lightbox_twidth'] );
	    $settings['config']['lightbox_theight']    = absint( $_POST['_soliloquy']['lightbox_theight'] );
	    $settings['config']['lightbox_tposition']  = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_soliloquy']['lightbox_tposition'] );
	    $settings['config']['lightbox_supersize'] = isset( $_POST['_soliloquy']['lightbox_supersize'] ) ? 1 : 0;

	    // Mobile
	    $settings['config']['mobile_lightbox']     = isset( $_POST['_soliloquy']['mobile_lightbox'] ) ? 1 : 0;
	
	    return $settings;
	
	}
		
	/**
	 * Saves the addon meta settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings  Array of settings to be saved.
	 * @param array $meta      Array of slide meta to use for saving.
	 * @param int $attach_id   The current attachment ID.
	 * @param int $post_id     The current post ID.
	 * @return array $settings Amended array of settings to be saved.
	 */
	function save_meta( $settings, $meta, $attach_id, $post_id ) {
	
	    $settings['slider'][$attach_id]['lightbox_enable'] = isset( $meta['lightbox_enable'] ) && $meta['lightbox_enable'] ? 1 : 0;
	    return $settings;
	
	}	
		
	/**
	 * Crops images based on lightbox settings for the slider.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings  Array of settings to be saved.
	 * @param int $post_id     The current post ID.
	 * @param object $post     The current post object.
	 */
	function crop( $settings, $post_id, $post ) {
	
	    // If the lightbox option and crop option are checked, crop images accordingly.
	    if ( isset( $settings['config']['lightbox_thumbs'] ) && $settings['config']['lightbox_thumbs'] ) {
	        $instance = Soliloquy_Metaboxes::get_instance();
	        $args     = apply_filters( 'soliloquy_crop_image_args',
	            array(
	                'position' => 'c',
	                'width'    => $instance->get_config( 'lightbox_twidth', $instance->get_config_default( 'lightbox_twidth' ) ),
	                'height'   => $instance->get_config( 'lightbox_theight', $instance->get_config_default( 'lightbox_theight' ) ),
	                'quality'  => 100,
	                'retina'   => false
	            )
	        );
	        $this->crop_images( $args, $post_id );
	    }
	
	}
	
	/**
	 * Callback function for cropping lightbox thumbs.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings  Array of settings to be saved.
	 * @param int $post_id     The current post ID.
	 * @param object $post     The current post object.
	 */
	function crop_images( $args, $post_id ) {
	
	    // Gather all available images to crop.
	    $slider_data = get_post_meta( $post_id, '_sol_slider_data', true );
	    $images      = ! empty( $slider_data['slider'] ) ? $slider_data['slider'] : false;
	    $common      = Soliloquy_Common::get_instance();
	
	    // Loop through the images and crop them.
	    if ( $images ) {
	        // Increase the time limit to account for large image sets and suspend cache invalidations.
	        set_time_limit( 0 );
	        wp_suspend_cache_invalidation( true );
	
	        foreach ( $images as $id => $item ) {
	            // Get the full image attachment. If it does not return the data we need, skip over it.
	            $image = wp_get_attachment_image_src( $id, 'full' );
	            if ( ! is_array( $image ) ) {
	                // Check for video/HTML slide and possibly use a thumbnail instead.
	                if ( ( isset( $item['type'] ) && 'video' == $item['type'] || isset( $item['type'] ) && 'html' == $item['type'] ) && ! empty( $item['thumb'] ) ) {
	                    $image = $item['thumb'];
	                } else {
	                    continue;
	                }
	            } else {
	                $image = $image[0];
	            }
	
	            // Allow image to be filtered to use a different thumbnail than the main image.
	            $image = apply_filters( 'soliloquy_cropped_image', $image, $id, $item, $args, $post_id );
	
	            // Generate the cropped image.
	            $cropped_image = $common->resize_image( $image, $args['width'], $args['height'], true, $args['position'], $args['quality'], $args['retina'] );
	
	            // If there is an error, possibly output error message, otherwise woot!
	            if ( is_wp_error( $cropped_image ) ) {
	                // If debugging is defined, print out the error.
	                if ( defined( 'SOLILOQUY_CROP_DEBUG' ) && SOLILOQUY_CROP_DEBUG ) {
	                    echo '<pre>' . var_export( $cropped_image->get_error_message(), true ) . '</pre>';
	                }
	            } else {
	                $slider_data['slider'][$id]['lb_thumb'] = $cropped_image;
	            }
	        }
	
	        // Turn off cache suspension and flush the cache to remove any cache inconsistencies.
	        wp_suspend_cache_invalidation( false );
	        wp_cache_flush();
	
	        // Update the slider data.
	        update_post_meta( $post_id, '_sol_slider_data', $slider_data );
	    }
	
	}	
    /**
     * Returns the singleton instance of the class.
     *
     * @since 2.3.0
     *
     * @return object The Soliloquy_Lightbox_Mextabox object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Soliloquy_Lightbox_Mextaboxes ) ) {
            self::$instance = new Soliloquy_Lightbox_Mextaboxes();
        }

        return self::$instance;

    }
	
}

$soliloquy_lightbox_metabox = Soliloquy_Lightbox_Mextaboxes::get_instance();
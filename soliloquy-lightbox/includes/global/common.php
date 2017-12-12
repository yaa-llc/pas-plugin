<?php
	
class Soliloquy_Lightbox_Common{
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

        // Load the base class object.
        $this->base = Soliloquy_Lightbox::get_instance();

    }
    
	/**
	 * Returns the available lightbox themes.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of lightbox theme data.
	 */
	function lightbox_themes() {
	
	    $themes = array(
	        array(
	            'value' => 'base',
	            'name'  => esc_attr__( 'Base', 'soliloquy-lightbox' )
	        ),
	        array(
	            'value' => 'classic',
	            'name'  => esc_attr__( 'Classic', 'soliloquy-lightbox' ),
	            'file'  => $this->base->file,
	        ),
	        array(
	            'value' => 'karisma',
	            'name'  => esc_attr__( 'Karisma', 'soliloquy-lightbox' ),
	            'file'  => $this->base->file,
	        ),
	        array(
	            'value' => 'metro',
	            'name'  => esc_attr__( 'Metro', 'soliloquy-lightbox' ),
	            'file'  => $this->base->file,
	        ),
	
	    );
	
	    return apply_filters( 'soliloquy_lightbox_themes', $themes );
	
	}
	
	/**
	 * Returns the available lightbox title positions.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of lightbox title data.
	 */
	function lightbox_titles() {
	
	    $titles = array(
	        array(
	            'value' => 'float',
	            'name'  => esc_attr__( 'Float', 'soliloquy-lightbox' )
	        ),
	        array(
	            'value' => 'inside',
	            'name'  => esc_attr__( 'Inside', 'soliloquy-lightbox' )
	        ),
	        array(
	            'value' => 'outside',
	            'name'  => esc_attr__( 'Outside', 'soliloquy-lightbox' )
	        ),
	        array(
	            'value' => 'over',
	            'name'  => esc_attr__( 'Over', 'soliloquy-lightbox' )
	        )
	    );
	
	    return apply_filters( 'soliloquy_lightbox_titles', $titles );
	
	}
	
	/**
	 * Returns the available lightbox transition effects.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of lightbox transition effects.
	 */
	function lightbox_transition_effects() {
	
	    $transitions = array(
	        array(
	            'value' => 'fade',
	            'name'  => esc_attr__( 'Fade', 'soliloquy-lightbox' )
	        ),
	        array(
	            'value' => 'elastic',
	            'name'  => esc_attr__( 'Elastic', 'soliloquy-lightbox' )
	        ),
	        array(
	            'value' => 'none',
	            'name'  => esc_attr__( 'No Effect', 'soliloquy-lightbox' )
	        )
	    );
	
	    return apply_filters( 'soliloquy_lightbox_transition_effects', $transitions );
	
	}
	
	/**
	 * Returns the available lightbox thumbnail positions.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of lightbox thumbnail data.
	 */
	function lightbox_thumbnail_positions() {
	
	    $positions = array(
	        array(
	            'value' => 'bottom',
	            'name'  => esc_attr__( 'Bottom', 'soliloquy-lightbox' )
	        ),
	        array(
	            'value' => 'top',
	            'name'  => esc_attr__( 'Top', 'soliloquy-lightbox' )
	        )
	    );
	
	    return apply_filters( 'soliloquy_lightbox_thumbnail_positions', $positions );
	
	}
	
    /**
     * Returns the singleton instance of the class.
     *
     * @since 2.3.0
     *
     * @return object The Soliloquy_Common object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Soliloquy_Lightbox_Common ) ) {
            self::$instance = new Soliloquy_Lightbox_Common();
        }

        return self::$instance;

    }
    	
}

$soliloquy_lightbox_common = Soliloquy_Lightbox_Common::get_instance();
<?php
defined( 'ABSPATH' ) || exit;
if ( ! class_exists('SLPlus_BaseClass_Object') ) {

	/**
	 * Class SLPlus_BaseClass_Object
	 *
	 * @property		SLPlus		$slplus
	 * @property		boolean		$uses_slplus		Set to true (default) if the object needs access to the SLPlus plugin object.
	 */
	class SLPlus_BaseClass_Object {
		protected $slplus;
		protected $uses_slplus = true;

		/**
		 * @param array $options
		 */
		function __construct( $options = array() ) {
			$this->set_properties( $options );

			if ( $this->uses_slplus ) {
				global $slplus_plugin;
				$this->slplus = $slplus_plugin;
			}

			$this->initialize();
		}

		/**
		 * @param string $property
		 *
		 * @return SLPPower
		 */
		function __get( $property ) {
			switch ( $property ) {
				case 'addon':
					global $slplus_plugin;
					if ( ! isset( $this->addon ) && property_exists( $this , 'slug' ) && ! empty( $this->slug ) ) {
						$this->addon = $slplus_plugin->AddOns->instances[ $this->slug ];
					} else {
						$this->addon = $slplus_plugin;
					}
					return $this->addon;

				default:
					if ( property_exists( $this , $property ) ) {
						return $this->$property;
					}
			}

			return null;
		}

		/**
		 * @param string $property
		 *
		 * @return bool
		 */
		function __isset( $property )  {
			return isset( $this->$property );
		}

		/**
		 * Do these things when this object is invoked. Override in your class.
		 */
		protected function initialize() {}

		/**
		 * Return an instance of the object which is also registered to the slplus global less the SLP_ part.
		 * @return mixed
		 */
		public static function get_instance() {
			static $instance;

			if ( ! isset( $instance ) ) {
				$class = get_called_class();
				$instance = new $class;
				$GLOBALS[ 'slplus' ]->add_object( $instance );
			}

			return $instance;
		}

		/**
		 * Set our properties.
		 *
		 * @param array $options
		 */
		public function set_properties( $options = array() ) {
			if ( ! empty( $options ) && is_array( $options ) ) {
				foreach ( $options as $property => $value ) {
					if ( property_exists( $this, $property ) ) {
						$this->$property = $value;
					}
				}
			}
		}
	}

}
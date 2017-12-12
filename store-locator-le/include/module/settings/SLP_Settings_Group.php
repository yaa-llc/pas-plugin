<?php
if ( ! class_exists( 'SLP_Settings_Group' ) ) {
	require_once( SLPLUS_PLUGINDIR . 'include/unit/SLP_Setting.php');

	/**
	 * Groups are collections of individual settings (items).
	 *
	 * @property        string                 $div_group    the div group we belong in
	 * @property        string                 $header       the header
	 * @property        string                 $intro        the starting text
	 * @property-read   SLP_Settings_default[] $items
	 * @property        string                 $slug         the slug
	 */
	class SLP_Settings_Group extends SLPlus_BaseClass_Object {
		public $div_group;
		public $intro;
		public $header;
		public $slug;
		public $SLP_Settings;

		public $items;

		/**
		 * Add an item to the group.
		 *
		 * @param mixed[] $params
		 */
		function add_item( $params ) {
			$item_object = $this->get_object_definition( $this->get_type( $params ) );

			if ( ! empty( $params['setting'] ) ) {
				$slug = sanitize_key( $params[ 'setting' ] );
				$this->items[ $slug ] = new $item_object( $params );
			} else {
				$this->items[] = new $item_object( $params );
			}
		}

		/**
		 * Map old types to new ones and set default to 'custom' if not set.
		 * 
		 * @param $params
		 *
		 * @return string
		 */
		private function get_type( $params ) {
			if ( empty( $params[ 'type'] ) ) {
				return 'custom';
			}

			switch ( $params['type']) {
				case 'submit_button':
					return 'submit';
				case 'text':
					return 'input';
				default:
					return $params['type'];
			}

		}

		/**
		 * Does this group have any items?
		 *
		 * @return bool
		 */
		function has_items() {
			return ! empty( $this->items );
		}

		/**
		 * Load a class file if it exists for the item type.
		 *
		 * @param string $type
		 * @return string
		 */
		private function get_object_definition( $type ) {
			if ( ! isset( $this->SLP_Settings->known_classes[ $type ] ) ) {
				$class = 'SLP_Settings_' . $type;
				$this->SLP_Settings->known_classes[ $type ] = $class;
			}
			return $this->SLP_Settings->known_classes[ $type ];
		}

		/**
		 * Render a group.
		 */
		function render_Group() {
			if ( ! $this->has_items() && empty( $this->intro )) { return; }
			$this->render_Header();
			if ( isset( $this->items ) ) {
				foreach ( $this->items as $item ) {
					$item->display();
				}
			}
			echo '</div></div>';
		}

		/**
		 * Output the group header.
		 */
		function render_Header() {
			echo
				"<div id='wpcsl_settings_group-{$this->slug}' class='settings-group ui-accordion-content-active' >" .
				"<h4 class='settings-header ui-accordion-header'>{$this->header}</h4>" .
				"<div class='inside'>" .
				(
				( $this->intro != '' ) ?
					"<div class='section_column_intro' id='wpcsl_settings_group_intro-{$this->slug}'>{$this->intro}</div>" :
					''
				);
		}

	}

}

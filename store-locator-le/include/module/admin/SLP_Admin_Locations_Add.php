<?php
defined( 'ABSPATH' ) || exit;

/**
 * Store Locator Plus basic admin user interface.
 *
 * @property-read   array[]      $group_params       The metadata needed to build a settings group.
 * @property-read   array[]      $section_params     The metadata needed to build a settings section.
 * @property-read   SLP_Settings $settings           SLP Settings Interface reference SLPlus->ManageLocations->settings
 * @property        SLPlus       $slplus
 */
class SLP_Admin_Locations_Add extends SLPlus_BaseClass_Object {
	private $group_params;
	private $locations_group = '';
	private $section_params;
	private  $settings;

	/**
	 * Initialize this object.
	 */
	public function initialize() {
		$this->settings = $this->slplus->Admin_Locations->settings;
		$this->locations_group          = $this->slplus->Text->get_text_string( 'location' );
		$this->section_params[ 'name' ] = $this->slplus->Text->get_text_string( 'add' );
		$this->section_params[ 'slug' ] = 'add';
		$this->slplus->currentLocation->reset();

		$title_text = $this->section_params[ 'name' ] . ' ' . $this->locations_group;

		$this->section_params[ 'opening_html' ] = <<< HTML
        <div class="x-reveal-modal add_location_modal">
            <h2 id="modalTitle">{$title_text}</h2>
            <div class="modal-inner">
                <input type="hidden" id="act" name="act" value="add" />
                <input type='hidden' name='id' id='id' value='' />
                <input type='hidden' name='locationID' id='locationID' value='' />
                <input type='hidden' name='linked_postid' data-field="linked_postid" value='' />
HTML;


		$this->section_params[ 'closing_html' ] = <<< HTML_END
				<footer class="footer-section">
					<div class="row">
						<div class="columns small-12">
							<div class="form-submit">
							{$this->submit_button()}
							</div>
						</div>
					</div>
				</footer>
			</div>
		</div>
HTML_END;


		$this->settings->add_section( $this->section_params );

		// Common params for all groups in this section.
		//
		$this->group_params[ 'section_slug' ] = $this->section_params[ 'slug' ];
		$this->group_params[ 'plugin' ]       = $this->slplus;

	}

	/**
	 * Create the address block
	 */
	private function address_block() {
		$this->group_params[ 'header' ]     = __( 'Location' , 'store-locator-le' );
		$this->group_params[ 'group_slug' ] = 'location';
		$this->group_params[ 'div_group' ]  = 'left_side';
		$this->settings->add_group( $this->group_params );

		$this->create_input( 'store' , __( 'Name' , 'store-locator-le' ) , $this->slplus->currentLocation->store );
		$this->create_input( 'address' , __( 'Street - Line 1' , 'store-locator-le' ) , $this->slplus->currentLocation->address );
		$this->create_input( 'address2' , __( 'Street - Line 2' , 'store-locator-le' ) , $this->slplus->currentLocation->address2 );
		$this->create_input( 'city' , __( 'City' , 'store-locator-le' ) , $this->slplus->currentLocation->city );
		$this->create_input( 'state' , __( 'State' , 'store-locator-le' ) , $this->slplus->currentLocation->state );
		$this->create_input( 'zip' , __( 'ZIP / Postal Code' , 'store-locator-le' ) , $this->slplus->currentLocation->zip );
		$this->create_input( 'country' , __( 'Country' , 'store-locator-le' ) , $this->slplus->currentLocation->country );

		$leave_lat_blank = __( 'Leave blank to have Google look up the latitude. ' , 'store-locator-le' );
		$this->create_input( 'latitude' , __( 'Latitude (N/S)' , 'store-locator-le' ) , $this->slplus->currentLocation->latitude , 'input' , $leave_lat_blank , $leave_lat_blank );

		$leave_lng_blank = __( 'Leave blank to have Google look up the longitude. ' , 'store-locator-le' );
		$this->create_input( 'longitude' , __( 'Longitude (E/W)' , 'store-locator-le' ) , $this->slplus->currentLocation->longitude , 'input' , $leave_lng_blank , $leave_lng_blank );

		$this->create_input( 'description' , __( 'Description' , 'store-locator-le' ) , $this->slplus->currentLocation->description , 'textarea' );
		$this->create_input( 'url' , $this->slplus->WPML->get_text( 'label_website' ) , $this->slplus->currentLocation->url );
		$this->create_input( 'email' , $this->slplus->WPML->get_text( 'label_email' ) , $this->slplus->currentLocation->email );
		$this->create_input( 'hours' , $this->slplus->WPML->get_text( 'label_hours' ) , $this->slplus->currentLocation->hours , 'textarea' );
		$this->create_input( 'phone' , $this->slplus->WPML->get_text( 'label_phone' ) , $this->slplus->currentLocation->phone );
		$this->create_input( 'fax' , $this->slplus->WPML->get_text( 'label_fax' ) , $this->slplus->currentLocation->fax );
		$this->create_input( 'image' , __( 'Image URL' , 'store-locator-le' ) , $this->slplus->currentLocation->image );

		$this->create_input( "private" , __( 'Private Entry' , 'store-locator-le' ) , '1'  , 'checkbox' , '' , __( 'Check this to prevent the location from showing up on user searches and the map.' , 'store-locator-le' ) );
	}

	/**
	 * Build the add or edit interface.
	 */
	public function build_interface() {

		$this->address_block();

		$this->details_block();         // Add Ons With Location Fields

		$this->extended_data_block();   // Add Ons Using Extended Data Fields

		$this->map();
	}

	/**
	 * Create form inputs.
	 *
	 * @param   string $fldName     name of the field, base name only
	 * @param   string $fldLabel    label to show ahead of the input
	 * @param   string $fldValue
	 * @param   string $inType
	 * @param   string $placeholder the placeholder for the input field (default: blank)
	 * @param   string $description The help text
	 *
	 * @return string the form HTML output
	 */
	private function create_input( $fldName , $fldLabel , $fldValue , $inType = 'input' , $placeholder = '' , $description = '' ) {
		$matches  = array();
		$matchStr = '/(.+)\[(.*)\]/';
		if ( preg_match( $matchStr , $fldName , $matches ) ) {
			$fldName    = $matches[ 1 ];
			$subFldName = '[' . $matches[ 2 ] . ']';
		} else {
			$subFldName = '';
		}

		$args = array(
			'group_params' => $this->group_params ,
			'label'        => $fldLabel ,
			'id'           => "{$fldName}{$subFldName}" ,
			'name'         => "{$fldName}{$subFldName}" ,
			'data_field'   => $this->slplus->currentLocation->is_base_field( $fldName ) ? $this->slplus->currentLocation->dbFieldPrefix . $fldName : $fldName ,
			'value'        => $fldValue ,
			'type'         => $inType ,
			'placeholder'  => $placeholder ,
			'description'  => $description ,
		);
		if ( $inType === 'checkbox' ) {
			$args[ 'display_value' ] = false;
		}

		$this->settings->add_ItemToGroup( $args );

		return '';
	}

	/**
	 * Add a details block for legacy add-on support.
	 */
	private function details_block() {
		$this->group_params[ 'header' ]     = __( 'Details' , 'store-locator-le' );
		$this->group_params[ 'group_slug' ] = 'details';
		$this->group_params[ 'div_group' ]  = 'left_side';
		$this->settings->add_group( $this->group_params );

		/**
		 * Filter to add HTML to the top of the add/edit location form.
		 *
		 * @filter     slp_edit_location_right_column
		 *
		 * @params     string      current HTML
		 */
		$details = apply_filters( 'slp_edit_location_right_column' , '' , $this->settings , $this->group_params );

		if ( ! empty( $details ) ) {
			$this->settings->add_ItemToGroup( array(
				                                  'group_params' => $this->group_params ,
				                                  'type'         => 'custom' ,
				                                  'custom'       => $details ,
				                                  'section'      => $this->section_params[ 'name' ] ,
			                                  ) );
		}
	}

	/**
	 * Add extended data to location add/edit form.
	 */
	private function extended_data_block() {
		$this->slplus->Admin_Locations->set_active_columns();
		$this->slplus->Admin_Locations->filter_active_columns();
		if ( empty( $this->slplus->Admin_Locations->active_columns ) ) {
			return;
		}

		$data = ( (int) $this->slplus->currentLocation->id > 0 ) ? $this->slplus->database->extension->get_data( $this->slplus->currentLocation->id ) : null;

		// For each extended data field, add an item.
		//
		$groups = array();
		foreach ( $this->slplus->Admin_Locations->active_columns as $data_field ) {
			$slug         = $data_field->slug;
			$display_type = $this->set_extended_data_display_type( $data_field );
			if ( $display_type === 'none' ) {
				continue;
			}

			$this->slplus->database->extension->set_options( $slug );

			$group_name = $this->set_extended_data_group( $data_field );

			// Group does not exist, add it to settings.
			//
			if ( ! in_array( $group_name , $groups ) ) {
				$groups[] = $group_name;

				$this->group_params[ 'header' ]       = $group_name;
				$this->group_params[ 'group_slug' ]   = sanitize_key( $group_name );
				$this->group_params[ 'div_group' ]    = 'left_side';
				$this->group_params[ 'section_slug' ] = $this->section_params[ 'slug' ];
				$this->group_params[ 'plugin' ]       = $this->slplus;

				$this->settings->add_group( $this->group_params );

				// Group exists, only need to set slug
				//
			} else {
				$this->group_params[ 'group_slug' ] = sanitize_key( $group_name );
				unset( $this->group_params[ 'header' ] );
			}

			// Standard data types
			//
			if ( $display_type !== 'callback' ) {
				$args = array(
					'group_params' => $this->group_params ,
					'label'        => $data_field->label ,
					'id'           => $slug ,
					'name'         => $slug ,
					'data_field'   => $slug ,
					'value'        => ( ( is_null( $data ) || ! isset( $data[ $slug ] ) ) ? '' : $data[ $slug ] ) ,
					'type'         => $display_type ,
					'description'  => $this->slplus->database->extension->get_option( $data_field->slug , 'help_text' ) ,
					'custom'       => $this->slplus->database->extension->get_option( $data_field->slug , 'custom' ) ,
				);
				if ( $display_type === 'checkbox' ) {
					$args[ 'display_value' ] = false;
				}

				$this->settings->add_ItemToGroup( $args );

				// Callback Display Type
				//
			} else {

				/**
				 * ACTION:     slp_add_location_custom_display
				 *
				 * Runs when the extended data display type is set to callback.
				 *
				 * @param   SLP_Settings $settings     SLP Settings Interface reference SLPlus->ManageLocations->settings
				 * @param   array[]      $group_params The metadata needed to build a settings group.
				 * @param   array[]      $data_field   The current extended data field meta.
				 */
				do_action( 'slp_add_location_custom_display' , $this->settings , $this->group_params , $data_field );
			}
		}
	}

	/**
	 * Render a map of where the location is.
	 */
	private function map() {
		$this->group_params[ 'header' ]     = __( 'Map' , 'store-locator-le' );
		$this->group_params[ 'group_slug' ] = 'map';
		$this->group_params[ 'div_group' ]  = 'right_side';
		$this->settings->add_group( $this->group_params );
		$this->settings->add_ItemToGroup( array(
			                                  'group_params' => $this->group_params ,
			                                  'type'         => 'custom' ,
			                                  'show_label'   => false ,
			                                  'custom'       => "<div id='location_map'></div>" ,
		                                  ) );

	}

	/**
	 * Set the display type.
	 *
	 * @param    array $data_field
	 *
	 * @return    string the display_type
	 */
	private function set_extended_data_display_type( $data_field ) {
		$display_type = $this->slplus->database->extension->get_option( $data_field->slug , 'display_type' );
		if ( is_null( $display_type ) ) {
			switch ( $data_field->type ) {

				case 'boolean':
					$display_type = 'checkbox';
					break;

				case 'text':
					$display_type = 'textarea';
					break;

				default:
					$display_type = 'text';
					break;
			}
		}

		return $display_type;
	}

	/**
	 * Set the SLPlus_Settings group name.
	 *
	 * @param    array $data_field
	 *
	 * @return    string        the SLPlus_Settings group name
	 */
	private function set_extended_data_group( $data_field ) {
		if ( is_null( $this->slplus->AddOns ) || empty( $data_field->option_values[ 'addon' ] ) ) {
			$group_name = __( 'Extended Data ' , 'store-locator-le' );

		} else {
			$group_name = $this->slplus->AddOns->instances[ $data_field->option_values[ 'addon' ] ]->name;
		}

		return $group_name;
	}

	/**
	 * Put the add/cancel button on the add/edit locations form.
	 *
	 * This is rendered AFTER other HTML stuff.
	 *
	 * @return string HTML of the form inputs
	 */
	public function submit_button() {
		$submit_text = $this->slplus->Text->get_text_string( 'save' );
		$cancel_text = $this->slplus->Text->get_text_string( 'cancel' );
		return <<<HTML
            <div id='slp_form_buttons'>
            <input type='submit' value='{$submit_text}' onClick='AdminUI.doAction("add")' alt='{$submit_text}' title='{$submit_text}' class='button-primary' />               
            <input type='button' class='button cancel_button' value='{$cancel_text}' alt='{$cancel_text}' title='{$cancel_text}'  />
            </div>
HTML;
		}
}

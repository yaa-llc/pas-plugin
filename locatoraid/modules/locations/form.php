<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Form_LC_HC_MVC
{
	public function inputs()
	{
		$return = array(
			'name' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> HCM::__('Name'),
				'validators' => array(
					$this->app->make('/validate/required')
					),
				),

			'street1' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> HCM::__('Street Address 1'),
				),

			'street2' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> HCM::__('Street Address 2'),
				),

			'city' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> HCM::__('City'),
				),

			'state' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> HCM::__('State'),
				),

			'zip' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> HCM::__('Zip Code'),
				),

			'country' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> HCM::__('Country'),
				),

			'phone' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> (isset($labels['phone']) && strlen($labels['phone'])) ? $labels['phone'] : HCM::__('Phone'),
				),

			'website' => array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> (isset($labels['website']) && strlen($labels['website'])) ? $labels['website'] : HCM::__('Website'),
				),
			);

		$return = $this->app
			->after( $this, $return )
			;

		$app_settings = $this->app->make('/app/settings');
	// remove unneeded and adjust labels if needed
		$always_show = array('name', 'street1', 'street2', 'city', 'state', 'zip', 'country');
		$input_names = array_keys( $return );
		foreach( $input_names as $k ){
			if( ! in_array($k, $always_show) ){
				$this_field_pname = 'fields:' . $k  . ':use';
				$this_field_conf = $app_settings->get($this_field_pname);
				if( ! $this_field_conf ){
					unset( $return[$k] );
					continue;
				}

				$this_field_pname = 'fields:' . $k  . ':label';
				$this_label = $app_settings->get($this_field_pname);
				if( strlen($this_label) ){
					$return[$k]['label'] = $this_label;
				}
			}
		}

		return $return;
	}
}
<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Coordinates_Form_LC_HC_MVC
{
	public function inputs()
	{
		$return = array(
			'latitude'	=> array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> HCM::__('Latitude')
				),

			'longitude'	=> array(
				'input'	=> $this->app->make('/form/text'),
				'label'	=> HCM::__('Longitude')
				),
			);
		return $return;
	}
}
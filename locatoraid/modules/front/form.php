<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Form_LC_HC_MVC
{
	public function inputs()
	{
		$return = array();

		$app_settings = $this->app->make('/app/settings');
		$label = $app_settings->get('front_text:search_field');
		if( $label === NULL ){
			$label = HCM::__('Address or Zip Code');
		}

		$return['search'] = $this->app->make('/form/text')
			->set_label( $label )
			;

		$return = $this->app
			->after( $this, $return )
			;

		return $return;
	}
}
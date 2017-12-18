<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Maps_Google_Conf_Form_HC_MVC
{
	public function inputs()
	{
		$return = array();

		$app_settings = $this->app->make('/app/settings');
		$api_key = $app_settings->get('maps_google:api_key');

		$api_key_help = $this->app->make('/html/element')->tag('div')
			->add(
				$this->app->make('/html/list')
					->add(
						HCM::__('Usage of the Google Maps APIs now requires an API key which you can get from the Google Maps developers website.')
						)
					->add(
						'<a href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend&keyType=CLIENT_SIDE&reusekey=true" target="_blank">' .
						HCM::__('Get Google Maps API key') .
						'</a>'
						)
				)
			;

		$label = HCM::__('Google Maps Browser API Key') . '<br>' . HCM::__('Or enter "none" to skip it');

		$return['maps_google:api_key'] = array(
			'input'	=> $this->app->make('/form/text'),
			'label'	=> $label,
			'validators'	=> array(
				$this->app->make('/validate/required')
				)
			);

		if( 1 OR ! strlen($api_key) ){
			$return['maps_google:api_key']['help'] = $api_key_help;
		}

	// if no api key is set then don't show other inputs
		if( strlen($api_key) ){
			$return['maps_google:scrollwheel'] =
				$this->app->make('/form/checkbox')
					->set_label( HCM::__('Enable Scroll Wheel Zoom') )
				;

			$style_help = 'Get your map style code from websites like <a target="_blank" href="http://www.snazzymaps.com/">Snazzy Maps</a> or <a target="_blank" href="http://www.mapstylr.com/">Map Stylr</a> and paste it in this textarea.';
			$return['maps_google:map_style'] = array(
				'input' => $this->app->make('/maps-google.conf/input-map-style'),
				'label'	=> HCM::__('Custom Map Style'),
				'help'	=> $style_help
				);
		}

		$return = $this->app
			->after( $this, $return )
			;

		return $return;
	}
}
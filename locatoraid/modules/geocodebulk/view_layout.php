<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class GeocodeBulk_View_Layout_LC_HC_MVC
{
	public function header( $total_count )
	{
		if( $total_count ){
			$return = sprintf(HCM::_n('%d Location To Geocode', '%d Locations To Geocode', $total_count), $total_count);
		}
		else {
			$return = HCM::__('No Locations To Geocode');
		}
		return $return;
	}

	public function menubar( $total_count )
	{
		$return = array();

		$return['list'] = $this->app->make('/html/ahref')
			->to('/locations')
			->add( $this->app->make('/html/icon')->icon('arrow-left') )
			->add( HCM::__('Locations') )
			;

		return $return;
	}

	public function render( $content, $total_count )
	{
		$menubar = $this->menubar($total_count);
		$header = $this->header($total_count);

		$out = $this->app->make('/layout/view/content-header-menubar')
			->set_content( $content )
			->set_header( $header )
			->set_menubar( $menubar )
			;

		return $out;
	}
}
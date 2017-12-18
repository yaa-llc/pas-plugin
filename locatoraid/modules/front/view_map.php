<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_View_Map_LC_HC_MVC
{
	public function render( $params = array() )
	{
		$style = array_key_exists('map-style', $params) ? $params['map-style'] : NULL;
		$map_id = 'hclc_map';
		$div = $this->app->make('/html/element')->tag('div')
			->add_attr('id', $map_id)
			->add_attr('class', 'hc-mb3-xs')
			->add_attr('class', 'hc-border')

			->add_attr('style', $style)
			;

		$app_settings = $this->app->make('/app/settings');
		$template = $app_settings->get('front_map:template');

		$template = $this->app->make('/html/element')->tag('script')
			->add_attr('type', 'text/template')
			->add_attr('id', 'hclc_map_template')
			->add( $template )
			;

		$out = $this->app->make('/html/element')->tag(NULL)
			->add( $div )
			->add( $template )
			;

		return $out;
	}
}
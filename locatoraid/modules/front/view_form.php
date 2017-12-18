<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_View_Form_LC_HC_MVC
{
	public function render( $params = array() )
	{
		$form = $this->app->make('/front/form');
		$app_settings = $this->app->make('/app/settings');

		if( isset($params['where-product']) && $params['where-product'] ){
			$form
				->unset_input('product')
				;
		}

		$form_values = array();
		if( isset($params['start']) && ($params['start'] != 'no') ){
			$form_values['search'] = $params['start'];
		}

		$search_form_id = 'hclc_search_form';

		$link_params = array(
			'search'	=> '_SEARCH_',
			'product'	=> '_PRODUCT_',
			'lat'		=> '_LAT_',
			'lng'		=> '_LNG_',
			);

		if( isset($params['id']) && $params['id'] ){
			$link_params['id'] = $params['id'];
			unset($params['radius']);
		}
		else {
			if( isset($params['limit']) ){
				$link_params['limit'] = $params['limit'];
			}
			if( isset($params['radius']) && (count($params['radius']) <= 1) ){
				$link_params['radius'] = $params['radius'];
			}
			if( isset($params['sort']) ){
				if( substr($params['sort'], -strlen('-reverse')) == '-reverse' ){
					$link_params['sort'] = array( substr($params['sort'], 0, -strlen('-reverse')), 0);
				}
				else {
					$link_params['sort'] = $params['sort'];
				}
			}
		}
// _print_r( $link_params );
// exit;

		reset( $params );
		foreach( $params as $k => $v ){
			if( ! (substr($k, 0, strlen('where-')) == 'where-') ){
				continue;
			}
			$k = substr( $k, strlen('where-') );
			$link_params[$k] = $v;
		}

		if( ! $link_params['product'] ){
			$link_params['product'] = '_PRODUCT_';
		}

		$link = $this->app->make('/http/uri')
			->mode('api')
			->url('/search', $link_params )
			;

	// radius link which will give us links to results
		$radius_link = '';
		if( isset($params['radius']) && (count($params['radius']) > 1) ){
			$radius_link_params = $link_params;

			$radius_link_params['radius'] = $params['radius'];
			unset( $radius_link_params['sort'] );
			// unset( $radius_link_params['limit'] );

			$radius_link = $this->app->make('/http/uri')
				->mode('api')
				->url('/search/radius', $radius_link_params )
				;
		}

		$form_attr = array(
			'id'				=> $search_form_id,
			'action'			=> $link,
			'data-radius-link'	=> $radius_link,
			'class'				=> 'hc-mb2',
			);
		if( isset($params['start']) && ($params['start'] != 'no') ){
			$form_attr['data-start'] = $params['start'];
		}

		$where_param = array();
		reset( $params );
		$take_where = array('where-country', 'where-zip', 'where-state', 'where-city');
		foreach( $params as $k => $v ){
			if( ! in_array($k, $take_where) ){
				continue;
			}
			if( ! strlen($v) ){
				continue;
			}

			$short_k = substr($k, strlen('where-'));
			$where_param[] = $short_k . ':' . $v;
		}

		if( $where_param ){
			$where_param = join(' ', $where_param);
			$form_attr['data-where'] = $where_param;
		}

		$helper = $this->app->make('/form/helper');
		$display_form = $helper->render( $form_attr );

		$inputs_view = $helper->prepare_render( $form->inputs(), $form_values );

		$out_inputs = $this->app->make('/html/list')
			->set_gutter(2)
			;
		foreach( $inputs_view as $k => $input ){
			$input_view = $this->app->make('/html/element')->tag('div')
				->add_attr('id', 'locatoraid-search-form-' . $k)
				->add( $input )
				;
			$out_inputs
				->add( $input_view )
				;
		}
		$out_inputs = $this->app->make('/html/element')->tag('div')
			->add_attr('id', 'locatoraid-search-form-inputs')
			->add( $out_inputs )
			;

		$btn_label = $app_settings->get('front_text:submit_button');
		if( $btn_label === NULL ){
			$btn_label = HCM::__('Search');
		}

		$out_buttons = $this->app->make('/html/element')->tag('input')
			->add_attr('type', 'submit')
			->add_attr('title', $btn_label )
			->add_attr('value', $btn_label )
			->add_attr('class', 'hc-block')
			->add_attr('id', 'locatoraid-search-form-button')
			;

		$form_view = $this->app->make('/html/grid')
			->set_gutter(2)
			;

		$form_view
			->add( $out_inputs, 8 )
			->add( $out_buttons, 4 )
			;

	// more results link
		$more_results_label = $app_settings->get('front_text:more_results');
		if( $more_results_label === NULL ){
			$more_results_label = HCM::__('More Results');
		}
	
		$more_results_link = $this->app->make('/html/element')->tag('a')
			->add_attr('class', 'hcj2-more-results')
			->add_attr('id', 'locatoraid-search-more-results')
			->add( $more_results_label )
			->add_attr('style', 'display: none; cursor: pointer;')
			;

		$form_view = $this->app->make('/html/list')
			->set_gutter(2)
			->add( $form_view )
			->add( $more_results_link )
			;

		$display_form
			->add( $form_view )
			;

		if( isset($params['id']) && $params['id'] ){
			$display_form = $this->app->make('/html/element')->tag('div')
				->add( $display_form )
				->add_attr('class', 'hc-hide')
				;
		}

		return $display_form;
	}
}
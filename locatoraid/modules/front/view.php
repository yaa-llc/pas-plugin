<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_View_LC_HC_MVC
{
	public function render( $pass_params = array() )
	{
		$enqueuer = $this->app->make('/app/enqueuer');
		$enqueuer
			->register_script( 'lc_front', 'modules/front/assets/js/front.js' )
			->enqueue_script( 'lc_front' )
			// ->register_style( 'lc_front', 'modules/front/assets/css/front.css' )
			// ->enqueue_style( 'lc_front' )
			;

	// parse params
		$default_params = array(
			'layout'		=> 'map|list',
			// 'start'			=> NULL,
			'start'			=> '',
			'limit'			=> 100,

			'group'			=> NULL,
			'list-group'	=> NULL,
			'sort'			=> NULL,
			'map-style'		=> 'height: 400px; width: 100%;',
			'list-style'	=> 'height: 400px; overflow-y: scroll;',

			// 'search-bias-country'	=> 'Australia',
			'search-bias-country'	=> '',
			'radius'				=> '10, 25, 50, 100, 200, 500',
			'id'			=> NULL,
			);

		$p = $this->app->make('/locations/presenter');
		$also_take = $p->database_fields();
		foreach( $also_take as $tk ){
			$default_params[ 'where-' . $tk ] = NULL;
		}
		$default_params[ 'where-product' ] = NULL;

// _print_r( $default_params );
// exit;
// _print_r( $pass_params );

		$params = array();
		foreach( $default_params as $k => $default_v ){
			if( ! array_key_exists($k, $pass_params) ){
				$params[$k] = $default_v;
				continue;
			}

			if( ! is_array($default_v) ){
				$params[$k] = $pass_params[$k];
				continue;
			}

			if( ! is_array($pass_params[$k]) ){
				$pass_params[$k] = array( $pass_params[$k] );
			}

			$v = array();
			foreach( $pass_params[$k] as $pass_v ){
				if( in_array($pass_v, $default_v) ){
					$v[] = $pass_v;
				}
			}

			if( ! $v ){
				$v = $default_v;
			}
			$params[$k] = $v;
		}

		if( isset($_GET['lpr-search']) ){
			$get_search = sanitize_text_field($_GET['lpr-search']);
			$params['start'] = $get_search;
		}

// _print_r( $pass_params );
// _print_r( $params );

	// parse layout
		$layout_conf_setting = $params['layout'];
		$allowed_components = array('map', 'list');

		$explode_by = '';
		$layout = array();
		if( strpos($layout_conf_setting, '|') !== FALSE ){
			$explode_by = '|';
		}
		elseif( strpos($layout_conf_setting, '/') !== FALSE ){
			$explode_by = '/';
		}

		if( $explode_by ){
			$layout_setting_array = explode($explode_by, $layout_conf_setting);
			foreach( $layout_setting_array as $ls ){
				$ls = strtolower(trim($ls));
				if( ! strlen($ls) ){
					continue;
				}
				if( ! in_array($ls, $allowed_components) ){
					continue;
				}
				$layout[] = $ls;
			}
			if( count($layout) > 1 ){
				$layout[] = $explode_by;
			}
		}
		else {
			$layout[] = $layout_conf_setting;
		}

		if( ! $layout ){
			$layout = array('map', 'list', '|');
		}

		if( $params['id'] ){
			$layout = array('map');
		}

		$view_type = 'stack';
		if( (count($layout) > 1) && ($layout[count($layout)-1] == '|') ){
			$view_type = 'grid';
		}

		$lc_front_params = array();
		if( $params['search-bias-country'] ){
			$search_bias_country = $params['search-bias-country'];
			$search_bias_country = explode(',', $search_bias_country);
			$lc_front_params['search_bias_country'] = $search_bias_country;
		}
		$enqueuer
			->localize_script( 'lc_front', $lc_front_params )
			;

	// parse radius
		if( isset($params['radius']) ){
			$supplied = $params['radius'];
			if( ! is_array($supplied) ){
				$supplied = explode(',', $supplied);
			}

			$final = array();
			foreach( $supplied as $r ){
				$r = trim($r);
				if( (string)(int) $r == $r ){
					$final[] = $r;
				}
			}
			$final = array_unique( $final );
			$params['radius'] = $final;
		}
		else {
			$params['radius'] = array();
		}

		$form = $this->app->make('/front/view/form')
			->render($params)
			;

		$form_view = $this->app->make('/html/element')->tag('div')
			->add( $form )
			->add_attr('class', 'hc-mb3')
			// ->add_attr('class', 'hc-p3')
			// ->add_attr('class', 'hc-border')
			;

		$views = array();
		if( in_array('map', $layout) ){
			$views['map'] = $this->app->make('/front/view/map')
				->render($params)
				;
			$widths['map'] = 8;
		}

		if( in_array('list', $layout) ){
			$need_list_params = array('group', 'list-style');
			$list_params = array();
			foreach( $params as $k => $v ){
				if( ! in_array($k, $need_list_params) ){
					continue;
				}
				$v = trim($v);
				if( ! strlen($v) ){
					continue;
				}
				$list_params[$k] = $v;
			}
			$views['list'] = $this->app->make('/front/view/list')
				->render($list_params)
				;
			$widths['list'] = 4;
		}

		if( count($layout) > 1 ){
			switch( $view_type ){
				case 'grid':
					$grid_id = 'hclc_grid';
					$out2 = $this->app->make('/html/grid')
						->set_gutter(2)
						;

					foreach( $layout as $k ){
						if( ! isset($views[$k]) ){
							continue;
						}
						$out2
							->add( $views[$k], $widths[$k] )
							;
					}

					$out2 = $this->app->make('/html/element')->tag('div')
						->add( $out2 )
						->add_attr('id', $grid_id)
						// ->add_attr('style', 'height: 400px;')
						;

					break;

				default:
					$out2 = $this->app->make('/html/element')->tag(NULL);
					foreach( $layout as $k ){
						if( ! isset($views[$k]) ){
							continue;
						}

						$out2
							->add(
								$this->app->make('/html/element')->tag('div')
									->add( $views[$k] )
									->add_attr('class', 'hc-mb3')
								)
							;
					}
					break;
			}
		}
		else {
			$out2 = $views[$layout[0]];
		}

		$form_view = $this->app->make('/html/element')->tag('div')
			->add( $form_view )
			->add_attr('id', 'locatoraid-form-container')
			;
		$out2 = $this->app->make('/html/element')->tag('div')
			->add( $out2 )
			->add_attr('id', 'locatoraid-map-list-container')
			;

		$out = $this->app->make('/html/element')->tag(NULL);

		$out
			->add( $form_view )
			->add( $out2 )
			;

		$out = $this->app
			->after( $this, $out )
			;

		return $out;
	}
}
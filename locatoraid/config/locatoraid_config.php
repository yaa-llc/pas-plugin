<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['route'][''] = '/locations/index/controller';

$config['route']['front.conf/list/mode/{to}']	= '/front.conf/list/mode/controller';
$config['route']['front.conf/map/mode/{to}']	= '/front.conf/map/mode/controller';

$config['route']['geocode/{id}/save/{lat}/{lng}']	= '/geocode/controller/save';

$config['route']['locations']				= '/locations/index/controller';
$config['route']['locations/{id}/edit']		= '/locations/edit/controller';
$config['route']['locations/{id}/update']	= '/locations/edit/controller/update';
$config['route']['locations/{id}/delete']	= '/locations/delete/controller';

$config['route']['locations/add']			= '/locations/new/controller/add';

$config['route']['locations.coordinates/{id}']			= '/locations.coordinates/index/controller';
$config['route']['locations.coordinates/{id}/update']	= '/locations.coordinates/index/controller/update';
$config['route']['locations.coordinates/{id}/reset']	= '/locations.coordinates/index/controller/reset';
$config['route']['acl/notallowed'] = '/acl.wordpress/notallowed';
$auth_wordpress_login_redirect = function( $app ){
	$redirect_to = wp_login_url();
	return $app->make('/http/view/response')
		->set_redirect($redirect_to) 
		;
};

$config['route']['auth/login'] = $auth_wordpress_login_redirect;
$config['route']['login'] = $auth_wordpress_login_redirect;

$config['route']['maps-google-conf'] = '/maps-google.conf/controller';
$config['route']['maps-google-conf/update'] = '/maps-google.conf/update-controller';
$config['route']['users.wordpress'] = '/users.wordpress/index/controller';
$config['route']['users.wordpress-conf'] = '/users.wordpress.conf/controller';
$config['route']['users.wordpress-conf/update'] = '/users.wordpress.conf/update-controller';
$config['after']['/conf/view/layout->tabs'][] = function( $app, $return )
{
	$return['app'] = array( 'app.conf', HCM::__('Configuration') );
	return $return;
};

$config['after']['/front/view'][] = function( $app, $return )
{
	$app_settings = $app->make('/app/settings');

	$this_pname = 'fields:directions:use';
	$this_pname_config = $app_settings->get($this_pname);
	if( ! $this_pname_config ){
		return $return;
	}

	$app->make('/app/enqueuer')
		->register_script( 'lc-directions-front', 'modules/directions.front/assets/js/directions.js' )
		->enqueue_script( 'lc-directions-front' )
		;

	return $return;
};
$config['after']['/locations/presenter->fields'][] = function( $app, $return )
{
	$return['directions'] = HCM::__('Directions');
	return $return;
};

$config['after']['/locations/presenter->present_front'][] = function( $app, $return, $search, $search_coordinates )
{
	if( ! ($return['latitude'] && $return['longitude']) ){
		return $return;
	}

	if( ( ($return['latitude'] == -1) OR ($return['longitude'] == -1) ) ){
		return $return;
	}

	if( ! $search_coordinates ){
		return $return;
	}
	if( ! is_array($search_coordinates) ){
		return $return;
	}

	$search_lat = array_shift( $search_coordinates );
	$search_lng = array_shift( $search_coordinates );
	if( ! ($search_lat && $search_lng) ){
		return $return;
	}

	$app_settings = $app->make('/app/settings');

	$this_pname = 'fields:directions:use';
	$this_pname_config = $app_settings->get($this_pname);
	if( ! $this_pname_config ){
		return $return;
	}

	$this_pname = 'fields:directions:label';
	$this_label = $app_settings->get($this_pname);
	$this_label = strlen($this_label) ? $this_label : HCM::__('Directions');

	$link_args = array(
		'class'			=> 'lpr-directions',
		'href'			=> '#',
		'data-to-lat'	=> $return['latitude'],
		'data-to-lng'	=> $return['longitude'],
		'data-from-lat'	=> $search_lat,
		'data-from-lng'	=> $search_lng,
		);

	$link_view = '<a';
	foreach( $link_args as $k => $v ){
		$link_view .= ' ' . $k . '="' . $v . '"';
	}

	$link_view .= '>';
	$link_view .= $this_label;
	$link_view .= '</a>';

	$return['directions'] = $link_view;
	return $return;
};

$config['after']['/app.conf/form'][] = function( $app, $return )
{
	$return['core:measure'] = array(
		'input'	=> $app->make('/form/radio')
			->set_options( 
				array(
					'mi'	=> HCM::__('Miles'),
					'km'	=> HCM::__('Km'),
					)
			),
		'label'	=> HCM::__('Measure Units'),
		);
	return $return;
};

$config['after']['/conf/view/layout->tabs'][] = function( $app, $return )
{
	$return['fields'] = array( 'front.conf/fields', HCM::__('Locations Details') );
	$return['front-map'] = array( 'front.conf/map', HCM::__('Details On Map') );
	$return['front-list'] = array( 'front.conf/list', HCM::__('Details In List') );
	$return['front-text'] = array( 'front.conf/text', HCM::__('Front Text') );
	return $return;
};

$config['after']['/app/settings->get'][] = function( $app, $return, $pname )
{
	switch( $pname ){
		case 'front_list:template':
			$app_settings = $app->make('/app/settings');
			$advanced = $app_settings->get('front_list:advanced');
			if( (! $advanced) OR (! strlen($return)) ){
				$return = $app->make('/front/view/list/template')
					->render()
					;
			}
			break;

		case 'front_map:template':
			$app_settings = $app->make('/app/settings');
			$advanced = $app_settings->get('front_map:advanced');
			if( (! $advanced) OR (! strlen($return)) ){
				$return = $app->make('/front/view/map/template')
					->render()
					;
			}
			break;
	}

	return $return;
};
$config['after']['/locations/edit/view'][] = function( $app, $return, $location )
{
	if( $location['latitude'] && $location['longitude'] ){
		return $return;
	}

	$geocode_view = $app->make('/geocode/view')
		->render( $location )
		;

	$out = $app->make('/html/list')
		->set_gutter( 2 )
		->add( $geocode_view )
		->add( $return )
		;

	return $out;
};

$config['after']['/root/link'][] = function( $app, $return )
{
	if( ! $return ){
		return $return;
	}

	// check module
	$module = 'geocode';
	if( ($module != $return) && (substr($return, 0, strlen($module . '/')) != $module . '/') ){
		return $return;
	}

	// check admin
	$logged_in = $app->make('/auth/lib')
		->logged_in()
		;
	$is_admin = $app->make('/acl/roles')
		->has_role( $logged_in, 'admin')
		;
	if( $is_admin ){
		return $return;
	}

	$return = FALSE;
	return $return;
};
$config['after']['/layout/top-menu'][] = function( $app, $return )
{
	$is_setup = $app->make('/setup/lib')
		->is_setup()
		;
	if( ! $is_setup ){
		return $return;
	}

	$not_geocoded_count = $app->make('/locations/commands/read')
		->execute(
			array(
				'count',
				array( 'latitude', '=', NULL ),
				array( 'longitude', '=', NULL )
				)
			);

	$not_geocoded_count2 = $app->make('/locations/commands/read')
		->execute(
			array(
				'count',
				array( 'latitude', '=', '0' ),
				array( 'longitude', '=', '0' )
				)
			);

	$not_geocoded_count = $not_geocoded_count + $not_geocoded_count2;

	if( ! $not_geocoded_count ){
		return $return;
	}

	$label = HCM::__('Geocode');
	$label .= ' (' . $not_geocoded_count . ')';

	$link = $app->make('/html/ahref')
		->to('/geocodebulk')
		->add( $app->make('/html/icon')->icon('exclamation') )
		->add( $label )
		;

	$return['geocodebulk'] = $link;

	return $return;
};

$config['after']['/root/link'][] = function( $app, $return )
{
	if( ! $return ){
		return $return;
	}

	// check module
	$module = 'geocodebulk';
	if( ($module != $return) && (substr($return, 0, strlen($module . '/')) != $module . '/') ){
		return $return;
	}

	// check admin
	$logged_in = $app->make('/auth/lib')
		->logged_in()
		;
	$is_admin = $app->make('/acl/roles')
		->has_role( $logged_in, 'admin')
		;
	if( $is_admin ){
		return $return;
	}

	$return = FALSE;
	return $return;
};
$config['after']['/layout/top-menu'][] = function( $app, $return )
{
	$link = $app->make('/html/ahref')
		->to('/locations')
		->add( $app->make('/html/icon')->icon('home') )
		->add( HCM::__('Locations') )
		;
	$return['location'] = array( $link, 1 );

	return $return;
};

$config['after']['/root/link'][] = function( $app, $return )
{
	if( ! $return ){
		return $return;
	}

	// check module
	$module = 'locations';
	if( ($module != $return) && (substr($return, 0, strlen($module . '/')) != $module . '/') ){
		return $return;
	}

	// check admin
	$logged_in = $app->make('/auth/lib')
		->logged_in()
		;
	$is_admin = $app->make('/acl/roles')
		->has_role( $logged_in, 'admin')
		;
	if( $is_admin ){
		return $return;
	}

	$return = FALSE;
	return $return;
};
$config['after']['/app.conf/form'][] = function( $app, $return )
{
	$return['locations_address:format'] = array(
		'input'	=> $app->make('/form/textarea')
			->set_rows(4)
			,
		'label'	=> HCM::__('Address Format'),
		'help'	=> $app->make('/html/list')
			->set_gutter(1)
			->add( HCM::__('Default Setting') )
			->add( 
				nl2br(
					'{STREET}
					{CITY} {STATE} {ZIP}
					{COUNTRY}'
					)
				),
		'validators' => array(
			$app->make('/validate/required')
			)
		);
	return $return;
};

$config['after']['/locations/edit/view'][] = function( $app, $return, $location )
{
	if( ! ($location['latitude'] && $location['longitude']) ){
		return $return;
	}

	$edit = 0;
	$coordinates_view = $app->make('/locations.coordinates/index/view')
		->render($location, $edit)
		;

	$out = $app->make('/html/list')
		->set_gutter( 2 )
		->add( $coordinates_view )
		->add( $return )
		;

	return $out;
};

$config['after']['/locations/edit/view/layout->menubar'][] = function( $app, $return, $e )
{
// coordinates
	$return['coordinates'] = 
		$app->make('/html/ahref')
			->to('/locations.coordinates/' . $e['id'])
			->add( $app->make('/html/icon')->icon('location') )
			->add( HCM::__('Edit Coordinates') )
		;

	return $return;
};

$config['after']['/locations/index/view->header'][] = function( $app, $return )
{
	$return['coordinates'] = HCM::__('Coordinates');
	return $return;
};

$config['after']['/locations/index/view->row'][] = function( $app, $return, $e )
{
	$p = $app->make('/locations.coordinates/presenter');

	$coordinates_view = $p->present_coordinates( $e );
	$geocoding_status = $p->geocoding_status( $e );
	if( $geocoding_status ){
		$coordinates_view = $app->make('/html/ahref')
			->to('/locations.coordinates/' . $e['id'])
			->add( $coordinates_view )
			;
	}

	$return['coordinates'] = $coordinates_view;
	return $return;
};

$config['after']['/locations/commands/update->prepare'][] = function( $app, $return, $id = NULL )
{
	$address_fields = array( 'street1', 'street2', 'city', 'state', 'zip', 'country' );

	$args = array();
	$args[] = $id;
	$args[] = array('select', array_merge(array('id'), $address_fields));
	$command = $app->make('/locations/commands/read');
	$current = $command->execute( $args );

//  check if we have address fields changed
	$changed = FALSE;
	foreach( $return as $k => $v ){
		if( ! array_key_exists($k, $current) ){
			continue;
		}

		if( $v != $current[$k] ){
			$changed = TRUE;
			break;
		}
	}

	if( ! $changed ){
		return $return;
	}

	$return['latitude'] = NULL;
	$return['longitude'] = NULL;

	return $return;
};
$config['after']['/locations/commands/create'][] = function( $app )
{
	$msg_key = 'locations-create';
	$msgbus = $app->make('/msgbus/lib');

	$msg = HCM::__('Location Added');
	$msgbus->add('message', $msg, $msg_key, TRUE);
};

$config['after']['/locations/commands/update'][] = function( $app )
{
	$msg_key = 'locations-update';
	$msgbus = $app->make('/msgbus/lib');

	$msg = HCM::__('Location Updated');
	$msgbus->add('message', $msg, $msg_key, TRUE);
};

$config['after']['/locations/commands/delete'][] = function( $app )
{
	$msg_key = 'locations-delete';
	$msgbus = $app->make('/msgbus/lib');

	$msg = HCM::__('Location Deleted');
	$msgbus->add('message', $msg, $msg_key, TRUE);
};

$config['after']['/locations/commands/read->args'][] = function( $app, $return )
{
// return $return;
	$my_return = array();
	$my_return[] = array('sort', 'priority', 'desc');
	$return = array_merge( $my_return, $return );
	return $return;
};

$config['after']['/locations/presenter->present_front'][] = function( $app, $return )
{
	if( isset($return['priority']) && (! $return['priority']) ){
		unset($return['priority']);
	}
	return $return;
};

$config['after']['/locations/presenter->fields'][] = function( $app, $return )
{
	$return['priority'] = HCM::__('Priority');
	return $return;
};

$config['after']['/locations/form'][] = function( $app, $return )
{
	$options = $app->make('/priority/presenter')
		->present_options()
		;

	$return['priority'] = array(
		'input'	=> $app->make('/form/radio')
			->set_options( $options ),
		'label'	=> HCM::__('Priority'),
		);
	return $return;
};

$config['after']['/locations/index/view->row'][] = function( $app, $return, $e )
{
	$app_settings = $app->make('/app/settings');
	$this_field_pname = 'fields:' . 'priority'  . ':use';
	$this_field_conf = $app_settings->get($this_field_pname);
	if( ! $this_field_conf ){
		return $return;
	}

	if( $e['priority'] ){
		$return['title'] = $app->make('/html/element')->tag('div')
			->add( $return['title'] )
			->add_attr('class', 'hc-p2')
			->add_attr('class', 'hc-border')
			->add_attr('class', 'hc-border-olive')
			->add_attr('class', 'hc-rounded')
			;
	}

	return $return;
};
$config['after']['/layout/top-menu'][] = function( $app, $return )
{
	$label = 'Locatoraid Pro';

	$link = $app->make('/html/ahref')
		->to( 'http://www.locatoraid.com/order/' )
		->set_outside( TRUE )
		->add( $app->make('/html/icon')->icon('star') )
		->add( $label )
		->add_attr( 'target', '_blank' )
		;
	$return['promo'] = array( $link, 200 );

	return $return;
};

$config['after']['/layout/view/content-header-menubar'][] = function( $app, $return )
{
	$promo = $app->make('/promo.wordpress/view');

	$return = $app->make('/html/list')
		->set_gutter(2)
		->add( $promo )
		->add( $return )
		;

	return $return;
};
$config['after']['/layout/top-menu'][] = function( $app, $return )
{
	$link = $app->make('/html/ahref')
		->to('/publish.wordpress')
		->add( $app->make('/html/icon')->icon('edit') )
		->add( 'Publish' )
		;
	$return['publish'] = array( $link, 80 );

	return $return;
};

$config['after']['/root/link'][] = function( $app, $return )
{
	if( ! $return ){
		return $return;
	}

	// check module
	$module = 'publish.wordpress';
	if( ($module != $return) && (substr($return, 0, strlen($module . '/')) != $module . '/') ){
		return $return;
	}

	// check admin
	$logged_in = $app->make('/auth/lib')
		->logged_in()
		;
	$is_admin = $app->make('/acl/roles')
		->has_role( $logged_in, 'admin')
		;
	if( $is_admin ){
		return $return;
	}

	$return = FALSE;
	return $return;
};
$config['after']['/app/enqueuer'][] = function( $app, $enqueuer )
{
	$enqueuer
		->register_script( 'hc', 'happ2/assets/js/hc2.js' )

		->register_style( 'hc-start', 'happ2/assets/css/hc-start.css' )
		->register_style( 'hc', 'happ2/assets/css/hc.css' )
		->register_style( 'font', 'https://fonts.googleapis.com/css?family=PT+Sans' )
		;

// enqueue
	$enqueuer
		->enqueue_script( 'hc' )
		;
};
$config['after']['/app/lib->isme'][] = function( $app, $return )
{
	if( ! $return ){
		return $return;
	}

	global $pagenow;
	$return = FALSE;

	$pages = array('edit.php', 'post.php', 'admin.php');
	$my_type_prefix = $app->app_short_name() . '-';
	$my_pages = $app->app_pages();

	if( ! is_admin() ){
		return $return;
	}

	if( ! in_array($pagenow, $pages) ){
		return $return;
	}

	switch( $pagenow ){
		case 'edit.php':
			$check_post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '';
			if( (substr($check_post_type, 0, strlen($my_type_prefix)) != $my_type_prefix) ){
				return $return;
			}
			break;

		case 'post.php':
			global $post;
			$check_post_type = isset($post->post_type) ? $post->post_type : '';
			if( (substr($check_post_type, 0, strlen($my_type_prefix)) != $my_type_prefix) ){
				return $return;
			}
			break;

		case 'admin.php':
			$check_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
			if( ! in_array($check_page, $my_pages) ){
				return $return;
			}
			break;

		default:
			return $return;
			break;
	}

	$return = TRUE;
	return $return;
};
$config['after']['/layout/top-menu'][] = function( $app, $return )
{
	$link = $app->make('/html/ahref')
		->to('/conf')
		->add( HCM::__('Configuration') )
		;
	$return['conf'] = array($link, 100);

	return $return;
};
$config['after']['/root/link'][] = function( $app, $return )
{
	if( ! $return ){
		return $return;
	}

	// check module
	// also check if it ends with .conf
	$module = 'conf';

	$is_me = FALSE;

	if( ($module == $return) OR (substr($return, 0, strlen($module . '/')) == $module . '/') ){
		$is_me = TRUE;
	}
	else {
		$dotmodule = '.' . $module;
		if( substr($return, -strlen($dotmodule)) == $dotmodule ){
			$is_me = TRUE;
		}
		if( strpos($return, $dotmodule . '/') !== FALSE ){
			$is_me = TRUE;
		}

		$dotmodule = $module . '.';
		if( substr($return, 0, strlen($dotmodule)) == $dotmodule ){
			$is_me = TRUE;
		}
	}

	if( ! $is_me ){
		return $return;
	}

	// check if admin
	$logged_in = $app->make('/auth/lib')
		->logged_in()
		;
	$is_admin = $app->make('/acl/roles')
		->has_role( $logged_in, 'admin')
		;
	if( $is_admin ){
		return $return;
	}

	$return = FALSE;
	return $return;
};
$config['after']['/conf/model->save'][] = function( $app, $return )
{
	$msg = HCM::__('Settings Updated');
	$msgbus = $app->make('/msgbus/lib');
	$msgbus->add('message', $msg);
};

$config['after']['/http/view/response->prepare_redirect'][] = function( $app, $return )
{
	$msgbus = $app->make('/msgbus/lib');
	$session = $app->make('/session/lib');

	$msg = $msgbus->get('message');
	if( $msg ){
		$session->set_flashdata('message', $msg);
	}
	$error = $msgbus->get('error');
	if( $error ){
		$session->set_flashdata('error', $error);
	}
	$warning = $msgbus->get('warning');
	if( $warning ){
		$session->set_flashdata('warning', $warning);
	}
	$debug = $msgbus->get('debug');
	if( $debug ){
		$session->set_flashdata('debug', $debug);
	}

	return $return;
};
$config['after']['/layout/view/body->content'][] = function( $app, $return )
{
	// in admin show by admin notices
	if( is_admin() ){
		return;
	}

	$flash_out = $app->make('/flashdata.layout/view')
		->render()
		;

	if( ! $flash_out ){
		return;
	}

	$return = $app->make('/html/list')
		->set_gutter(1)
		->add( $flash_out )
		->add( $return )
		;

	return $return;
};
$config['after']['/html/icon'][] = function( $app, $return, $src )
{
	$convert = array(
		'networking'	=> 'networking',
		'star-o'	=> 'star-empty',
		// 'plus'		=> 'plus-alt', // simple plus appears off center vertically
		'cog'		=> 'admin-generic',
		'user'		=> 'admin-users',
		'group'		=> 'groups',
		'times'		=> 'dismiss',
		'check'		=> 'yes',
		'status'	=> 'post-status',
		'list'		=> 'editor-ul',
		'history'	=> 'book',
		'exclamation'	=> 'warning',
		'printer'		=> 'media-text',
		'home'			=> 'admin-home',
		'star'			=> 'star-filled',

		'purchase'		=> 'products',
		'sale'			=> 'cart',
		'inventory'		=> 'admin-page',
		'copy'			=> 'admin-page',
		'chart'			=> 'chart-bar',
		'message'		=> 'email',
		'holidays'		=> 'palmtree',
		'connection'	=> 'admin-links',
		'view'			=> 'visibility',
		'password'		=> 'admin-network',

		'confirmed'		=> 'star-filled',
		'pending'		=> 'star-half',
		'tools'			=> 'admin-tools',
	);

	$return = isset($convert[$return]) ? $convert[$return] : $return;

	if( $return && strlen($return) ){
		if( substr($return, 0, 1) == '&' ){
			$return = $app->make('/html/element')->tag('span')
				->add( $return )
				->add_attr('class', 'hc-mr1')
				->add_attr('class', 'hc-ml1')
				->add_attr('class', 'hc-char')
				;
		}
		else {
			$return = $app->make('/html/element')->tag('i')
				->add_attr('class', 'dashicons')
				->add_attr('class', 'dashicons-' . $return)
				->add_attr('class', 'hc-dashicons')
				;
		}
	}

	return $return;
};

$config['after']['/app/enqueuer'][] = function( $app, $enqueuer )
{
	$enqueuer
		->enqueue_style( 'hc' )
		;
};

$config['after']['/app/enqueuer->register_script'][] = function( $app, $handle, $path )
{
	$wp_handle = 'hc2-script-' . $handle;
	$path = $app->make('/layout.wordpress/path')
		->full_path( $path )
		;
	wp_register_script( $wp_handle, $path, array('jquery') );
};

$config['after']['/app/enqueuer->register_style'][] = function( $app, $handle, $path )
{
	$skip = array('reset', 'style', 'form', 'font', 'hc-start');
	if( in_array($handle, $skip) ){
		return;
	}

	$wp_handle = 'hc2-style-' . $handle;
	$path = $app->make('/layout.wordpress/path')
		->full_path( $path )
		;
	wp_register_style( $wp_handle, $path );
};

$config['after']['/app/enqueuer->enqueue_script'][] = function( $app, $handle )
{
	$wp_handle = 'hc2-script-' . $handle;
// echo "ENQUEUEWP '$wp_handle'<br>";
	wp_enqueue_script( $wp_handle );
};

$config['after']['/app/enqueuer->enqueue_style'][] = function( $app, $handle )
{
	$wp_handle = 'hc2-style-' . $handle;
	wp_enqueue_style( $wp_handle );
};

$config['after']['/app/enqueuer->localize_script'][] = function( $app, $handle, $params )
{
	$wp_handle = 'hc2-script-' . $handle;
	$js_var = 'hc2_' . $handle . '_vars'; 
	wp_localize_script( $wp_handle, $js_var, $params );
};

$config['after']['/layout/view/body'][] = function( $app )
{
	$enqueuer = $app->make('/app/enqueuer');
	return;
};

$config['after']['/app/enqueuer'][] = function( $app, $enqueuer )
{
	static $done = FALSE;
	if( $done ){
		return;
	}
	$done = TRUE;

	$enqueuer
		->register_script( 'gmaps', 'happ2/modules/maps_google/assets/js/gmaps.js' )
		;

	$app_settings = $app->make('/app/settings');
	$api_key = $app_settings->get('maps_google:api_key');
	if( is_array($api_key) ){
		$api_key = array_shift($api_key);
	}

	if( $api_key == 'none' ){
		$api_key = '';
	}
	$api_key = trim($api_key);

	$map_style = $app_settings->get('maps_google:map_style');
	$scrollwheel = $app_settings->get('maps_google:scrollwheel');
	$scrollwheel = $scrollwheel ? TRUE : FALSE;

	$params = array(
		'api_key'		=> $api_key,
		'map_style'		=> $map_style,
		'scrollwheel'	=> $scrollwheel,
		);

	$enqueuer
		->localize_script( 'gmaps', $params )
		;

	$enqueuer
		->enqueue_script( 'gmaps' )
		;
};
$config['after']['/conf/view/layout->tabs'][] = function( $app, $return )
{
	$return['maps-google'] = array( 'maps-google-conf', HCM::__('Google Maps') );
	return $return;
};
$config['after']['/form/helper->render'][] = function( $app, $return )
{
	$security = $app->make('/security/lib');

	$csrf_name = $security->get_csrf_token_name();
	$csrf_value = $security->get_csrf_hash();

	if( strlen($csrf_name) && strlen($csrf_value) ){
		$hidden = $app->make('/form/hidden')
			->render( $csrf_name, $csrf_value )
			;

		$return->add(
			$app->make('/html/element')->tag('div')
				->add_attr('style', 'display:none')
				->add( $hidden )
			);
	}

	return $return;
};
$config['after']['/root/link'][] = function( $app, $return )
{
	if( ! $return ){
		return $return;
	}

	// check module
	$module = 'users';

	$is_me = FALSE;

	if( ($module == $return) OR (substr($return, 0, strlen($module . '/')) == $module . '/') ){
		$is_me = TRUE;
	}
	else {
		$dotmodule = $module . '.';
		if( substr($return, 0, strlen($dotmodule)) == $dotmodule ){
			$is_me = TRUE;
		}
	}

	if( ! $is_me ){
		return $return;
	}

	// check admin
	$logged_in = $app->make('/auth/lib')
		->logged_in()
		;
	$is_admin = $app->make('/acl/roles')
		->has_role( $logged_in, 'admin')
		;
	if( $is_admin ){
		return $return;
	}

	$return = FALSE;
	return $return;
};
$config['after']['/conf/view/layout->tabs'][] = function( $app, $return )
{
	$return['users'] = array( 'users.wordpress', HCM::__('Users') );
	return $return;
};

$config['after']['/users/index/view/layout->menubar'][] = function( $app, $return )
{
	$return['settings'] = $app->make('/html/ahref')
		->to('/users.wordpress-conf')
		->add( $app->make('/html/icon')->icon('cog') )
		->add( HCM::__('Settings') )
		;

	if( current_user_can('create_users') ){
		$link = admin_url( 'user-new.php' );
		$return['add'] = $app->make('/html/ahref')
			->to($link)
			->add( $app->make('/html/icon')->icon('plus') )
			->add( HCM::__('Add New') )
			;
	}

	return $return;
};

$config['after']['/conf/view/layout->tabs'][] = function( $app, $return )
{
	$return['wordpress-users'] = array( 'users.wordpress-conf', HCM::__('Roles') );
	return $return;
};
$config['after']['/root/link'][] = function( $app, $return )
{
	if( ! $return ){
		return $return;
	}

	// check module
	$module = 'users.wordpress-conf';
	if( ($module != $return) && (substr($return, 0, strlen($module . '/')) != $module . '/') ){
		return $return;
	}

	// check admin
	$wp_always_admin = $app->make('/acl.wordpress/roles')->always_admin();
	$wp_user = wp_get_current_user();
	if( array_intersect($wp_always_admin, (array) $wp_user->roles) ){
		return $return;
	}

	$return = FALSE;
	return $return;
};

$config['settings']['fields:directions:label'] = HCM::__('Directions');
$config['settings']['fields:directions:use'] = 1;

$config['settings']['front_map:directions:show'] = 1;
$config['settings']['front_map:directions:w_label'] = FALSE;

$config['settings']['front_list:directions:show'] = 1;
$config['settings']['front_list:directions:w_label'] = FALSE;

$config['settings']['core:measure'] = 'mi';

$config['settings']['fields:name:use'] = TRUE;
$config['settings']['fields:name:label'] = '';
$config['settings']['fields:address:use'] = TRUE;
$config['settings']['fields:address:label'] = '';
$config['settings']['fields:distance:use'] = 1;
$config['settings']['fields:distance:label'] = '';
$config['settings']['fields:website:label'] = '';
$config['settings']['fields:website:use'] = 1;
$config['settings']['fields:phone:label'] = HCM::__('Phone');
$config['settings']['fields:phone:use'] = 1;

$config['settings']['front_map:advanced'] = 0;
$config['settings']['front_map:template'] = '';

$config['settings']['front_map:name:show'] = 1;
$config['settings']['front_map:name:w_label'] = FALSE;
$config['settings']['front_map:address:show'] = 1;
$config['settings']['front_map:address:w_label'] = FALSE;
$config['settings']['front_map:distance:show'] = 1;
$config['settings']['front_map:distance:w_label'] = 0;
$config['settings']['front_map:website:show'] = 1;
$config['settings']['front_map:website:w_label'] = FALSE;
$config['settings']['front_map:phone:show'] = 1;
$config['settings']['front_map:phone:w_label'] = 1;

$config['settings']['front_list:advanced'] = 0;
$config['settings']['front_list:template'] = '';

$config['settings']['front_list:name:show'] = 1;
$config['settings']['front_list:name:w_label'] = FALSE;
$config['settings']['front_list:address:show'] = 1;
$config['settings']['front_list:address:w_label'] = FALSE;
$config['settings']['front_list:distance:show'] = 1;
$config['settings']['front_list:distance:w_label'] = 0;
$config['settings']['front_list:website:show'] = 1;
$config['settings']['front_list:website:w_label'] = FALSE;
$config['settings']['front_list:phone:show'] = 1;
$config['settings']['front_list:phone:w_label'] = 1;

$config['settings']['front_text:submit_button'] = HCM::__('Search');
$config['settings']['front_text:search_field'] = HCM::__('Address or Zip Code');
$config['settings']['front_text:more_results'] = HCM::__('More Results');


$config['settings']['locations_address:format'] = '
{STREET}
{CITY} {STATE} {ZIP}
{COUNTRY}
';

$config['settings']['fields:priority:use'] = 1;

$config['settings']['maps_google:api_key'] = '';
$config['settings']['maps_google:scrollwheel'] = 1;
$config['settings']['maps_google:map_style'] = '';

$wp_roles = new WP_Roles();
$wordpress_roles = $wp_roles->get_names();

foreach( $wordpress_roles as $role_value => $role_name ){
	$default = 1;

	switch( $role_value ){
		case 'administrator':
		case 'developer':
			$config['settings']['wordpress_users:role_' . $role_value ] = 1;
			break;

		default:
			$config['settings']['wordpress_users:role_' . $role_value ] = 0;
			break;
	}
}
$config['bootstrap'][] = function( $app )
{
	$shortcode = 'locatoraid';
	$view = $app->make('/front/view/shortcode');
	add_shortcode( $shortcode, array($view, 'render'));
};
$config['bootstrap'][] = function( $app )
{
	// $view = $app->make('/promo.wordpress/view/notices');
	// add_action( 'admin_notices', array($view, 'render') );
};
$config['bootstrap'][] = function( $app )
{
	$is_me = $app->make('/app/lib')
		->isme()
		;
	if( $is_me ){
		$enqueuer = $app->make('/app/enqueuer');
	}
};
$config['bootstrap'][] = function( $app )
{
	$is_me = $app->make('/app/lib')
		->isme()
		;
	if( ! $is_me ){
		return;
	}

	$view = $app->make('/flashdata.wordpress.layout/view-admin-notices');
	add_action( 'admin_notices', array($view, 'render') );
};
$config['bootstrap'][] = function( $app )
{
	$is_me = $app->make('/app/lib')
		->isme()
		;
	if( ! $is_me ){
		return;
	}

	$app_settings = $app->make('/app/settings');
	$api_key = $app_settings->get('maps_google:api_key');
	if( is_array($api_key) ){
		$api_key = array_shift($api_key);
	}

	if( strlen($api_key) ){
		return;
	}

	$slug = $app->make('/http/uri')
		->slug()
		;

	if( substr($slug, 0, strlen('setup')) == 'setup' ){
		return;
	}

	if( in_array($slug, array('conf/update', 'maps-google-conf', 'maps-google-conf/update')) ){
		return;
	}

// redirect to field edit
	$uri = $app->make('/http/uri')
		->mode('web')
		->url('maps-google-conf')
		;
	$view = $app->make('/http/view/response')
		->set_redirect($uri)
		->render()
		;
	echo $view;
	exit;
};
$config['bootstrap'][] = function( $app )
{
	$setup = $app->db->table_exists('migrations');
	if( ! $setup ){
		$app->migration->init();
		if( ! $app->migration->current()){
			hc_show_error( $app->migration->error_string());
		}
	}
};
$config['migration']['locations'] = 2;
$config['migration']['locations.conf'] = 1;

$config['migration']['priority'] = 1;
$config['migration']['conf'] = 1;

$config['migration']['maps_google.conf'] = 1;

$config['migration']['ormrelations'] = 2;
$config['migration']['users.wordpress.conf'] = 1;

$config['alias']['/auth/lib'] = '/auth.wordpress/lib';
$config['alias']['/http/lib/client'] = 'lib/client';
$config['alias']['/users/commands/read'] = '/users.wordpress/commands/read';


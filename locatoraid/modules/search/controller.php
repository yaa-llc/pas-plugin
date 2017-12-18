<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Search_Controller_LC_HC_MVC
{
	public function execute()
	{
		$uri = $this->app->make('/http/uri');

		$id = $uri->param('id');

		$search = $uri->param('search');
		$lat = $uri->param('lat');
		$lng = $uri->param('lng');
		$limit = $uri->param('limit');
		$sort = $uri->param('sort');
		$radius = $uri->param('radius');
		$offset = $uri->param('offset');

		if( $id ){
			$search = NULL;
			$lat = NULL;
			$lng = NULL;
			$limit = 1;
			$sort = NULL;
			$radius = NULL;
			$offset = NULL;
		}

		$results = array();

		$command = $this->app->make('/locations/commands/read');
		$command_args = array();

		$command_args[] = array('osearch', $search);
		$command_args[] = array('with', '-all-');
		if( $id ){
			$command_args[] = $id;
		}
		if( $limit ){
			$command_args[] = array('limit', $limit);
		}
		if( $sort ){
			$command_args[] = array('sort', $sort);
		}
		if( $offset ){
			$command_args[] = array('offset', $offset);
		}

		$p = $this->app->make('/locations/presenter');
		$also_take = $p->database_fields();
		$also_take[] = 'product';

		reset( $also_take );
		foreach( $also_take as $tk ){
			$v = $uri->param($tk);
			if( is_array($v) ){
				$command_args[] = array($tk, 'IN', $v);
			}
			else {
				if( ! strlen($v) ){
					continue;
				}
				if( substr($v, 0, 1) == '_' ){
					continue;
				}
				$command_args[] = array($tk, '=', $v);
			}
		}

		$search_coordinates = array();
		if( $lat && $lng && ($lat != '_LAT_') && ($lng != '_LNG_') ){
			$search_coordinates = array($lat, $lng);

			$command_args[] = array('lat', $lat);
			$command_args[] = array('lng', $lng);

			if( $radius ){
				$radius = (int) $radius;
				$command_args[] = array('having', 'computed_distance', '<=', $radius);
			}
		}

		$results = $command->execute( $command_args );
		if( $results && $limit && ($limit == 1) ){
			$results = array( $results['id'] => $results );
		}

		$view = $this->app->make('/search/view')
			->render($results, $search, $search_coordinates)
			;

		return $view;
	}
}
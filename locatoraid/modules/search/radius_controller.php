<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Search_Radius_Controller_LC_HC_MVC
{
	public function execute()
	{
		$uri = $this->app->make('/http/uri');

		$search = $uri->param('search');
		$lat = $uri->param('lat');
		$lng = $uri->param('lng');
		$limit = $uri->param('limit');

		$link_params = array();
		$link_params['search'] = $search;

		$results = array();

		$command_args = array();

		$p = $this->app->make('/locations/presenter');
		$also_take = $p->database_fields();
		$also_take[] = 'product';

		$command_args[] = array('osearch', $search);

		$radius_count = array();
		if( $lat && $lng && ($lat != '_LAT_') && ($lng != '_LNG_') ){
			$search_coordinates = array($lat, $lng);
			$command_args[] = array('lat', $lat);
			$command_args[] = array('lng', $lng);

			$link_params['lat'] = $lat;
			$link_params['lng'] = $lng;

			reset( $also_take );
			foreach( $also_take as $tk ){
				$v = $uri->param($tk);

				if( is_array($v) ){
					$command_args[] = array($tk, 'IN', $v);
					$link_params[$tk] = array('IN', $v);
				}
				else {
					if( ! strlen($v) ){
						continue;
					}
					if( substr($v, 0, 1) == '_' ){
						continue;
					}
					$command_args[] = array($tk, '=', $v);
					$link_params[$tk] = $v;
				}
			}
		}

		$command_args[] = 'count';
		$command = $this->app->make('/locations/commands/read');

		$radiuses = $uri->param('radius');
		if( ! $radiuses ){
			$radiuses = array( 10, 20, 50, 100, 200, 500 );
		}
		if( ! is_array($radiuses) ){
			$radiuses = array( $radiuses );
		}
		rsort( $radiuses, SORT_NUMERIC );

		$results = array();

		$last_count = 0;
		reset( $radiuses );
		foreach( $radiuses as $r ){
			$r = (int) $r;
			$this_command_args = $command_args;
			$this_command_args[] = array( 'radius', $r );
			$this_count = $command->execute( $this_command_args );

			if( ! $this_count ){
				break;
			}

			if( $this_count == $last_count ){
				array_pop($results); 
			}

		// remove everything above
			if( $limit && ($limit < $this_count) ){
				$results = array();
			}

			$results[ $r ] = $this_count;
			$last_count = $this_count;
		}
		$results = array_reverse( $results, TRUE );

		$return = array();
		foreach( $results as $radius => $count ){
			$this_link_params = $link_params;
			if( $radius ){
				$this_link_params['radius'] = $radius;
			}
			if( $limit ){
				$this_link_params['limit'] = $limit;
			}

			$link = $this->app->make('/http/uri')
				->url('/search', $this_link_params)
				;
			$return[] = array( $link, $count );
		}

		$return = json_encode( $return );
		return $return;
	}
}
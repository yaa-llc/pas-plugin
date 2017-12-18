<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Commands_Read_LC_HC_MVC
{
	public function args( $return = array() )
	{
		if( ! is_array($return) ){
			$return = array( $return );
		}

		$return[] = array('sort', 'name', 'asc');

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;
		return $return;
	}

	public function execute( $args = array() )
	{
		$args = $this->args( $args );

		$command = $this->app->make('/commands/read')
			->set_table('locations')
			->set_search_in( array('name', 'street1', 'street2', 'city', 'state', 'zip', 'country') );
			;
		$args = $command->prepare_args( $args, array('lat', 'lng', 'radius') );

		$mylat = NULL;
		$mylng = NULL;
		$radius = NULL;

		foreach( $args['SKIP'] as $arg ){
			$k = array_shift( $arg );
			switch( $k ){
				case 'lng':
					$mylng = array_shift( $arg );
					break;

				case 'lat':
					$mylat = array_shift( $arg );
					break;

				case 'radius':
					$radius = array_shift( $arg );
					$radius = (int) $radius;
					break;
			}
		}

		if( $mylat && $mylng ){
			$app_settings = $this->app->make('/app/settings');
			$measure = $app_settings->get('core:measure');

		/* miles */
			if( $measure == 'mi' ){
				$nau2measure = 1.1508;
				$per_grad = 69;
			}
		/* km */
			else {
				$nau2measure = 1.852; 
				$per_grad = 111.04;
			}

			$formula = "
				DEGREES(
				ACOS(
					SIN(RADIANS(latitude)) * SIN(RADIANS($mylat))
				+	COS(RADIANS(latitude)) * COS(RADIANS($mylat))
				*	COS(RADIANS(longitude - ($mylng)))
				) * 60 * $nau2measure
				)
				";

			if( $args['COUNT'] ){
				if( $radius ){
					$args['WHERE'][] = array( $formula, '<=', $radius );
				}
			}
			else {
				$add_select = $formula . ' AS computed_distance';
				if( $args['SELECT'] ){
					$args['SELECT'][] = $add_select;
				}
				else {
					$args['SELECT'] = array('locations.*', $add_select);
				}
				$args['SORT'] = array_merge( array(array('computed_distance', 'asc')), $args['SORT'] );
			}
		}

		$return = $command
			->execute( $args )
			;

		$return = $this->app
			->after( $this, $return )
			;
		return $return;
	}
}
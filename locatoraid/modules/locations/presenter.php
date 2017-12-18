<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Presenter_LC_HC_MVC
{
	public function fields()
	{
		$return = array(
			'name'			=> HCM::__('Name'),
			'address'		=> HCM::__('Address'),
			'distance'		=> HCM::__('Distance'),
			'phone'			=> HCM::__('Phone'),
			'website'		=> HCM::__('Website'),
			);

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

		return $return;
	}

	public function fields_labels()
	{
		$return = $this->fields();

		$app_settings = $this->app->make('/app/settings');
		$always_show = array('name', 'address');

		$keys = array_keys($return);
		foreach( $keys as $k ){
			if( ! in_array($k, $always_show) ){
				$this_field_pname = 'fields:' . $k  . ':use';
				$this_field_conf = $app_settings->get($this_field_pname);
				if( ! $this_field_conf ){
					unset( $return[$k] );
					continue;
				}
			}

			$this_field_pname = 'fields:' . $k  . ':label';
			$this_label = $app_settings->get($this_field_pname);
			if( strlen($this_label) ){
				$return[ $k ] = $this_label;
			}
		}
		return $return;
	}

	public function database_fields()
	{
		$return = array('name', 'street1', 'street2', 'city', 'state', 'zip', 'country', 'phone', 'website', 'latitude', 'longitude');

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

		return $return;
	}

	public function present_icon_url( $data )
	{
		$return = NULL;
		$return = $this->app
			->after( array($this, __FUNCTION__), $return, $data )
			;
		return $return;
	}

	public function present_icon( $data )
	{
		$icon_url = $this->present_icon_url( $data );
		if( ! $icon_url ){
			$icon_url = '//maps.google.com/mapfiles/ms/micons/red-dot.png';
		}

		$return = $this->app->make('/html/element')->tag('img')
			->add_attr('src', $icon_url)
			->add_attr('style', 'max-width:100%;')
			;
		return $return;
	}

	public function present_distance( $data )
	{
		$return = isset($data['distance']) ? $data['distance'] : NULL;
		if( ! $return ){
			return $return;
		}

		$app_settings = $this->app->make('/app/settings');
		$measure = $app_settings->get('core:measure');

		if( $return < 1 ){
			$return = ceil( $return * 100 ) / 100;
		}
		elseif( $return < 100 ){
			$return = ceil( $return * 10 ) / 10;
		}
		else {
			$return = ceil( $return );
		}
		$return = $return . ' ' . $measure;

		return $return;
	}

	public function present_address( $data )
	{
		$parts = array();
		$take = array( 'street1', 'street2', 'city', 'state', 'zip', 'country' );

		foreach( $take as $t ){
			$part = isset($data[$t]) ? $data[$t] : '';
			if( strlen($part) ){
				$parts[$t] = $part;
			}
			else {
				$parts[$t] = '';
			}
		}

		$app_settings = $this->app->make('/app/settings');
		$template = $app_settings->get('locations_address:format');

		$template = trim($template);

		if( strlen($template) ){
			if( isset($parts['street2']) && strlen($parts['street2']) ){
				$parts['street'] = $parts['street1'] . "\n" . $parts['street2'];
			}
			else {
				$parts['street'] = $parts['street1'];
			}

			$return = $template;
			foreach( $parts as $k => $v ){
				$return = str_replace( '{' . strtoupper($k) . '}', $v, $return );
			}
		}
		else {
			$return = join(', ', $parts);
		}

		$return = trim( $return );
		$return = nl2br( $return, FALSE );
		return $return;
	}

	public function present_title( $data )
	{
		$return = isset($data['name']) ? $data['name'] : NULL;
		return $return;
	}

	protected function _prepare_front( $key, $value )
	{
		$return = $value;

		switch( $key ){
			case 'website':
				$ok = FALSE;
				$value = trim($value);
				if( ! strlen($value) ){
					return;
				}

				$href = $value;
				$prfx = array('http://', 'https://', '//');
				foreach( $prfx as $prf ){
					if( substr($href, 0, strlen($prf)) == $prf ){
						$ok = TRUE;
						break;
					}
				}

				if( ! $ok ){
					$href = 'http://' . $href;
					// $href = '//' . $href;
				}

				$app_settings = $this->app->make('/app/settings');
				$this_pname = 'fields:website:label';
				$this_label = $app_settings->get($this_pname);
				$this_label = strlen($this_label) ? $this_label : $value;

				$return = '<a href="' . $href . '" target="_blank">' . $this_label . '</a>';
				break;

			default:
				$email_regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i';

				if(
					preg_match('/^misc/', $key) &&
					preg_match('/(\.jpg|\.png|\.gif|\.svg)$/i', $value)
					){
					$return = '<img src="' . $value . '" style="max-width: 95%;">';
				}
				elseif(
					preg_match('/^misc/', $key) &&
					(
					preg_match('/^https?\:\/\//', $value) OR
					preg_match('/^\/\//', $value)
					)
					){

					$app_settings = $this->app->make('/app/settings');
					$this_pname = 'fields:' . $key . ':label';
					$this_label = $app_settings->get($this_pname);
					$this_label = strlen($this_label) ? $this_label : $value;

					$return = '<a href="' . $value . '" target="_blank">' . $this_label . '</a>';
				}
				elseif(
					preg_match('/^misc/', $key) &&
					preg_match($email_regex, $value)
					){
					// $field_view = '<a href="mailto:' . $e[$f['name']] . '" target="_blank">' . $f['title'] . '</a>';
					// $field_view = '<a href="mailto:' . $e[$f['name']] . '" target="_blank">' . $f['title'] . '</a>';

					$app_settings = $this->app->make('/app/settings');
					$this_pname = 'fields:' . $key . ':label';
					$this_label = $app_settings->get($this_pname);
					$this_label = strlen($this_label) ? $this_label : $value;

					$return = '<a href="mailto:' . $value . '" target="_blank">' . $this_label . '</a>';
				}
				break;
		}

		return $return;
	}

	public function present_front( $data, $search = NULL, $search_coordinates = array() )
	{
		$return = $data;
		$return['address'] = $this->present_address( $return );
		$return['name'] = $this->present_title( $return );

	// process to show urls and emails
		foreach( array_keys($return) as $k ){
			$return[$k] = $this->_prepare_front( $k, $return[$k] );
		}

		$return = $this->app
			->after( array($this, __FUNCTION__), $return, $search, $search_coordinates )
			;

		return $return;
	}
}
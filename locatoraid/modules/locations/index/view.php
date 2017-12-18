<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Index_View_LC_HC_MVC
{
	public function render( $entries, $total_count, $page = 1, $search = '', $per_page = 5 )
	{
		$header = $this->header();

		$rows = array();
		reset( $entries );
		foreach( $entries as $e ){
			$rows[ $e['id'] ] = $this->row( $e );
		}

		$out = $this->app->make('/html/list')
			->set_gutter(1)
			;

		$submenu = $this->app->make('/html/list-inline')
			->set_gutter(2)
			;

		if( $total_count > $per_page ){
			$pager = $this->app->make('/html/pager')
				->set_total_count( $total_count )
				->set_current_page( $page )
				->set_per_page($per_page)
				;

			$submenu
				->add( $pager )
				;
		}

		$search_view = $this->app->make('/modelsearch/view');
		$submenu
			->add( $search_view->render($search) )
			;

		$out
			->add( $submenu )
			;

		if( $rows ){
			$table = $this->app->make('/html/table-responsive')
				->set_no_footer(FALSE)
				->set_header($header)
				->set_rows($rows)
				;

			$table = $this->app->make('/html/element')->tag('div')
				->add( $table )
				->add_attr('class', 'hc-border')
				;

			$out
				->add( $table )
				;
		}
		elseif( $search ){
			$msg = HCM::__('No Matches');
			$out
				->add( $msg )
				;
		}

		return $out;
	}

	public function header()
	{
		$return = array(
			'title' 	=> HCM::__('Location'),
			// 'address' 	=> HCM::__('Address'),
			);

		$return = $this->app
			->after( array($this, __FUNCTION__), $return )
			;

		return $return;
	}

	public function row( $e )
	{
		$return = array();
		if( ! $e ){
			return $return;
		}

		$p = $this->app->make('/locations/presenter');

		$title_view = $p->present_title( $e );
		$title_view = $this->app->make('/html/ahref')
			->to('/locations/' . $e['id'] . '/edit')
			->add( $title_view )
		// imitate wordpress
			->add_attr('class', 'hc-bold')
			->add_attr('class', 'hc-fs4')
			->add_attr('class', 'hc-decoration-none')
			;

		$return['id']		= $e['id'];
		$id_view = $this->app->make('/html/element')->tag('span')
			->add_attr('class', 'hc-fs2')
			->add_attr('class', 'hc-muted2')
			->add( 'id: ' . $e['id'] )
			;

		$address_view = $p->present_address( $e );

		$title_view = $this->app->make('/html/list')
			->set_gutter(0)
			->add( $title_view )
			->add( $address_view )
			->add( $id_view )
			;

		$return['id_view']	= $id_view;
		$return['title'] = $title_view;
		$return['address'] = $address_view;

		$return = $this->app
			->after( array($this, __FUNCTION__), $return, $e )
			;

		return $return;
	}
}
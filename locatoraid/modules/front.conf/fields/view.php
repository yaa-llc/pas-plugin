<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Conf_Fields_View_LC_HC_MVC
{
	public function render()
	{
		$app_settings = $this->app->make('/app/settings');
		$values = $app_settings->get();
		$form = $this->app->make('/front.conf/fields/form');

		$helper = $this->app->make('/form/helper');
		$inputs_view = $helper->prepare_render( $form->inputs(), $values );

		$out_inputs = $this->app->make('/html/table-responsive');

		$header = array(
			'field'	=> HCM::__('Field'),
			'label'	=> HCM::__('Label'),
			'use'	=> HCM::__('Use'),
			);

		$rows = array();
		$p = $this->app->make('/locations/presenter');
		$fields = $p->fields();
		foreach( $fields as $fn => $flabel ){
			$label_pname = 'fields:' . $fn  . ':label';
			$use_pname = 'fields:' . $fn  . ':use';

			$this_row = array();
			$this_row['field'] = $flabel;
			if( isset($inputs_view[$label_pname]) ){
				$this_row['label'] = $inputs_view[$label_pname];
			}

			if( isset($inputs_view[$use_pname]) ){
				$this_row['use'] = $inputs_view[$use_pname];
			}
			else {
				$this_field_conf = $app_settings->get($use_pname);
				if( $this_field_conf === TRUE ){
					$this_row['use'] = $this->app->make('/html/element')->tag('span')
						->add( $this->app->make('/html/icon')->icon('check') )
						->add_attr('class', 'hc-olive')
						;
				}
				elseif( $this_field_conf === FALSE ){
					$this_row['use'] = $this->app->make('/html/element')->tag('span')
						->add( $this->app->make('/html/icon')->icon('times') )
						->add_attr('class', 'hc-maroon')
						;
				}
			}

			$rows[$fn] = $this_row;
		}

		$out_inputs
			->set_header( $header )
			->set_rows( $rows )
			;

		$out_buttons = $this->app->make('/html/list')
			->set_gutter(2)
			->add(
				$this->app->make('/html/element')->tag('input')
					->add_attr('type', 'submit')
					->add_attr('title', HCM::__('Save') )
					->add_attr('value', HCM::__('Save') )
					->add_attr('class', 'hc-theme-btn-submit')
					->add_attr('class', 'hc-theme-btn-primary')
					->add_attr('class', 'hc-block')
				)
			;

		$link = $this->app->make('/http/uri')
			->url('/front.conf/fields/update')
			;
		$out = $helper
			->render( array('action' => $link) )
			->add( 
				$this->app->make('/html/grid')
					->set_gutter(2)
					->add( $out_inputs, 9, 12 )
					->add( $out_buttons, 3, 12 )
				)
			;

		return $out;
	}
}
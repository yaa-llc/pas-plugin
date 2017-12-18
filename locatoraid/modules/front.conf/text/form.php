<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Conf_Text_Form_LC_HC_MVC
{
	public function inputs()
	{
		$return = array(
			'front_text:submit_button'	=> array(
				'input'		=> $this->app->make('/form/text'),
				'label'		=> HCM::__('Submit Button'),
				'validators' => array(
					$this->app->make('/validate/required'),
					),
				'help'		=> HCM::__('Search'),
				),

			'front_text:search_field'	=> array(
				'input'		=> $this->app->make('/form/text'),
				'label'		=> HCM::__('Search Field'),
				'validators' => array(
					$this->app->make('/validate/required'),
					),
				'help'		=> HCM::__('Address or Zip Code'),
				),

			'front_text:more_results'	=> array(
				'input'		=> $this->app->make('/form/text'),
				'label'		=> HCM::__('More Results Link'),
				'validators' => array(
					$this->app->make('/validate/required'),
					),
				'help'		=> HCM::__('More Results'),
				),
		);

		return $return;
	}
}
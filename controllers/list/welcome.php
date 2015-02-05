<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	protected $_endpoint_list = array (
			'computer_languages' => '/list/computer_languages',
			'open_source_licenses' => '/list/open_source_licenses',
			'countries' => '/list/countries',
			'divisions' => '/list/divisions',
			'languages' => '/list/languages',
		);

	public function index () {
		$this->output->set_content_type('application/json; charset=utf-8');
		$data['usage'] = $this->_endpoint_list;
		$this->load->view('usage', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/list/welcome.php */

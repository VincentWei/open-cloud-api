<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	protected $_endpoint_list = array (
			'installation' => '/install/',
			'access_token' => '/access_token/',
			'data_in_list' => '/list/',
		);

	public function index() {
		$this->output->set_content_type('application/json; charset=utf-8');
		$data['usage'] = $this->_endpoint_list;
		$this->load->view('usage', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */

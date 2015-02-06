<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Access_Token extends CI_Controller {

	protected $_endpoint_list = array (
					'get_access_token' => '/access_token/get/{app_key}',
				);

	public function index() {
		$this->output->set_content_type('application/json; charset=utf-8');
		$data['usage'] = $_endpoint_list;
		$this->load->view('usage', $data);
	}

	public function get ($app_key) {
		if (!isset ($app_key)) {
			return;
		}

		if (preg_match ("/^[a-z0-9]{64}$/", $app_key)) {
			$this->load->model ('Access_token_model', '', TRUE);
			$token = $this->Access_token_model->generate ($app_key);
			if ($token === Access_token_model::ERR_NO_SUCH_APP_KEY) {
				show_error ('No such app key.', 400);
			}
			else if ($token === Access_token_model::ERR_FILE_SYSTEM) {
				show_error ('Internal server error.', 500);
			}
			else {
				$this->output->set_content_type('application/json; charset=utf-8');
				$data['endpoint'] = $this->_endpoint_list['get_access_token'];
				$data['message'] = $token;
				$this->load->view('message', $data);
			}
		}
		else {
			show_error ('Bad app key.', 400);
		}
	}
}

/* End of file access_token.php */

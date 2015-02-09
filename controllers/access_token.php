<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This file is a part of Open Cloud API Project.
 *
 * Open Cloud API project tries to provide free APIs for internet apps
 * to fetch public structured data (such as country list) or some
 * common computing services (such as generating QR code).
 *
 * For more information, please refer to:
 *
 *		http://www.fullstackengineer.net/zh/project/open-cloud-api-zh
 *		http://www.fullstackengineer.net/en/project/open-cloud-api-en
 *
 * Copyright (C) 2015 WEI Yongming
 * <http://www.fullstackengineer.net/zh/engineer/weiyongming>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class Access_Token extends MY_Controller {

	protected $_endpoint_list = array (
					'get_access_token' => '/access_token/get/{app_key}/{caller_id}',
				);

	public function index() {
		$this->output->set_content_type('application/json; charset=utf-8');
		$data['usage'] = $_endpoint_list;
		$this->load->view('usage', $data);
	}

	public function get ($app_key, $caller_id) {
		if (!isset ($app_key) || !isset ($caller_id)) {
			return;
		}

		if (!parent::_check_callback ($data)) {
			return;
		}

		if (preg_match ("/^[a-z0-9]{64}$/", $app_key) && preg_match ("/^[a-z0-9]{32}$/", $caller_id)) {
			$this->load->model ('Access_token_model', '', TRUE);
			$token = $this->Access_token_model->generate ($app_key, $caller_id);
			if ($token === Access_token_model::ERR_NO_SUCH_APP_KEY) {
				show_error ('No such app key.', 400);
			}
			else if ($token === Access_token_model::ERR_FILE_SYSTEM) {
				show_error ('Internal server error.', 500);
			}
			else {
				$data['endpoint'] = $this->_endpoint_list['get_access_token'];
				$data['message'] = $token;
				$this->load->view('message', $data);
			}
		}
		else {
			show_error ('Bad app key or caller identifier.', 400);
		}
	}
}

/* End of file access_token.php */

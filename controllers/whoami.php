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

class Whoami extends MY_Controller {

	protected $_endpoint_list = array (
					'return_my_ip_address' => '/whoami/ip_address',
					'return_my_user_agent' => '/whoami/user_agent',
					'return_my_browser' => '/whoami/browser',
				);

	public function index() {
		$this->output->set_content_type('application/json; charset=utf-8');
		$data['usage'] = $this->_endpoint_list;
		$this->load->view('usage', $data);
	}

	public function ip_address () {
		$this->load->helper ('misc');

		if (!parent::_check_callback ($data)) {
			return;
		}

		$data['endpoint'] = $this->_endpoint_list['return_my_ip_address'];
		$data['message'] = get_real_ip ();
		$this->load->view('message', $data);
	}

	public function user_agent () {
		if (!parent::_check_callback ($data)) {
			return;
		}

		$user_agent = isset ($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
		$data['endpoint'] = $this->_endpoint_list['return_my_user_agent'];
		$data['message'] = $user_agent;
		$this->load->view('message', $data);
	}

	public function browser () {
		if (!parent::_check_callback ($data)) {
			return;
		}

		$data['endpoint'] = $this->_endpoint_list['return_my_browser'];
		$data['items'] = get_browser (NULL, TRUE);
		// FIXME: A work-around for bad character returned by get_browser
		$data['items']['browser_name_regex'] = trim ($data['items']['browser_name_regex'], '§');
		$this->load->view('list', $data);
	}
}

/* End of file access_token.php */

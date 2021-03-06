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

class Echome extends MY_Controller {

	protected $_endpoint_list = array (
					'echo_untouched' => '/echome/untouched/{token}{/param}',
					'echo_base64' => '/echome/base64/{token}{/param}',
					'echo_app_keyed_hash' => '/echome/app_keyed_hash/{token}/{algo}{/param}',
				);

	public function index() {
		$this->output->set_content_type('application/json; charset=utf-8');
		$data['usage'] = $this->_endpoint_list;
		$this->load->view('usage', $data);
	}

	public function untouched ($token, $param = '') {
		$endpoint_name = 'echo_untouched';

		if (!isset ($token)) {
			return;
		}

		if (!parent::_check_callback ($data)) {
			return;
		}

		$param = urldecode ($param);
		if (!parent::_check_token ($token, $endpoint_name, $param)) {
			return;
		}

		$data['endpoint'] = $this->_endpoint_list[$endpoint_name];
		$data['message'] = $param;
		$this->load->view('message', $data);
	}

	public function base64 ($token, $param = '') {
		$endpoint_name = 'echo_base64';

		if (!isset ($token)) {
			return;
		}

		if (!parent::_check_callback ($data)) {
			return;
		}

		$param = urldecode ($param);
		if (!parent::_check_token ($token, $endpoint_name, $param)) {
			return;
		}

		$data['endpoint'] = $this->_endpoint_list[$endpoint_name];
		$data['message'] = base64_encode ($param);
		$this->load->view('message', $data);
	}

	public function app_keyed_hash ($token, $algo, $param = '') {
		$endpoint_name = 'echo_app_keyed_hash';

		if (!isset ($token) || !isset ($algo)) {
			return;
		}

		$algo = urldecode ($algo);
		if (!in_array ($algo, hash_algos ())) {
			show_error ('Bad hash algorithm.', 400);
			return;
		}

		$param = urldecode ($param);
		if (!parent::_check_token ($token, $endpoint_name, $param)) {
			return;
		}

		if (!parent::_check_callback ($data)) {
			return;
		}

		$data['endpoint'] = $this->_endpoint_list[$endpoint_name];
		$data['message'] = hash_hmac ($algo, $param, $this->app_key);
		$this->load->view('message', $data);
	}
}

/* End of file echome.php */

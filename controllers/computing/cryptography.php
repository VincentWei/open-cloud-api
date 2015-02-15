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

class Cryptography extends MY_Controller {

	protected $_endpoint_list = array (
					'get_algos' => '/computing/cryptography/algos',
					'get_hash' => '/computing/cryptography/hash/{token}/{algo}{/param}',
					'get_hash_hmac' => '/computing/cryptography/hash_hmac/{token}/{algo}/{key}{/param}',
				);

	public function index() {
		$this->output->set_content_type('application/json; charset=utf-8');
		$data['usage'] = $this->_endpoint_list;
		$this->load->view('usage', $data);
	}

	public function algos () {
		if (!parent::_check_callback ($data)) {
			return;
		}

		$data['items'] = hash_algos ();
		$data['endpoint'] = $this->_endpoint_list['get_algos'];
		$this->load->view('list', $data);
	}

	public function hash ($token, $algo, $param = '') {
		$endpoint_name = 'get_hash';

		if (!isset ($token) || !isset ($algo)) {
			return;
		}

		$algo = urldecode ($algo);
		if (!in_array ($algo, hash_algos ())) {
			show_error ('Bad hash algorithm.', 400);
			return;
		}

		if (!parent::_check_callback ($data)) {
			return;
		}

		$param = urldecode ($param);
		if (!parent::_check_token ($token, $endpoint_name, "hash ($algo, $param)")) {
			return;
		}

		$data['endpoint'] = $this->_endpoint_list[$endpoint_name];
		$data['message'] = hash ($algo, $param);
		$this->load->view('message', $data);
	}

	public function hash_hmac ($token, $algo, $key, $param = '') {
		$endpoint_name = 'get_hash_hmac';

		if (!isset ($token) || !isset ($algo) || !isset ($key)) {
			return;
		}

		$algo = urldecode ($algo);
		if (!in_array ($algo, hash_algos ())) {
			show_error ('Bad hash algorithm.', 400);
			return;
		}

		if (!parent::_check_callback ($data)) {
			return;
		}

		$param = urldecode ($param);
		if (!parent::_check_token ($token, $endpoint_name, "hash_hmac ($algo, $param, $key)")) {
			return;
		}

		$data['endpoint'] = $this->_endpoint_list[$endpoint_name];
		$data['message'] = hash_hmac ($algo, $param, $key);
		$this->load->view('message', $data);
	}
}

/* End of file cryptography.php */

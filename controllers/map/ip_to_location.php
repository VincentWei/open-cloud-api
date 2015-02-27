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
 *	    http://www.fullstackengineer.net/zh/project/open-cloud-api-zh
 *	    http://www.fullstackengineer.net/en/project/open-cloud-api-en
 *
 * Copyright (C) 2015 Ai Di
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

class Ip_To_Location extends MY_Controller {

	protected $_ttl_cache = 86400;
	protected $_endpoint_list = array (
					'get_location_by_ip' => '/map/ip_to_location/get/{token}{/ip}',
				);

	public function index () {
		$this->output->set_content_type('application/json; charset=utf-8');
		$data['usage'] = $this->_endpoint_list;
		$this->load->view('usage', $data);
	}

	public function get ($token, $ip = FALSE) {
		$endpoint_name = 'get_location_by_ip';

		if (!isset ($token)) {
			return;
		}

		if ($ip === FALSE || !preg_match ('/^((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d))))$/', $ip)) {
			$this->load->helper ('misc');
			$ip = get_real_ip ();
		}

		if (!$this->_check_callback ($data)) {
			return;
		}

		if (!$this->_check_token ($token, $endpoint_name, $ip)) {
			return;
		}

		$data['message'] = $ip;
		$data['endpoint'] = $this->_endpoint_list[$endpoint_name];

		require_once ('application/libraries/17mon/IP.class.php');
		$data['items'] = IP::find ($ip);

		require_once ('application/libraries/qqwry/qqwry.php');
		$QQWry = new QQWry;
		$ifErr = $QQWry->QQWry ($ip);
		$data['extras'][0] = iconv ('gbk', 'utf-8', $QQWry->Country);
		$data['extras'][1] = iconv ('gbk', 'utf-8', $QQWry->Local);

		$this->load->view('list', $data);
	}
}

/* End of file ip_to_location.php */

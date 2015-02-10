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

class Chinese_Converter extends MY_Controller {

	protected $_ttl_cache = 86400;
	protected $_endpoint_list = array (
					'translate_chinese' => '/map/chinse_converter/translate/{token}/{locale}{/text}',
				);

	public function index () {
		$this->output->set_content_type('application/json; charset=utf-8');
		$data['usage'] = $this->_endpoint_list;
		$this->load->view('usage', $data);
	}

	public function translate ($token, $locale, $text = '汉语') {
		$endpoint_name = 'translate_chinese';

		if (!isset ($token) || !isset($locale)) {
			return;
		}

		$locale = str_replace ('_', '-', $locale);
		$locale = strtolower ($locale);
		if (!in_array ($locale, array ('zh-cn', 'zh-tw', 'zh-hk', 'zh-mo', 'zh-sg', 'zh-my'))) {
			show_error ('Bad locale.', 400);
			return;
		}

		if (!$this->_check_callback ($data)) {
			return;
		}

		if (!$this->_check_token ($token, $endpoint_name, "$locale ($text)")) {
			return;
		}

		$this->load->driver ('cache', array('adapter' => 'apc', 'backup' => 'file'));
		$params = array('cache_obj' => $this->cache, 'cache_get_func' => 'my_static_cache_get',
				'cache_set_func' => 'my_static_cache_set');
		$this->load->library ('ZhConverter', $params);
		$data['message'] = $this->zhconverter->translate (urldecode ($text), $locale);
		$data['endpoint'] = $this->_endpoint_list[$endpoint_name];
		$this->load->view('message', $data);
	}

}

/* End of file chinese_converter.php */

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

class Chinese_Pinyin extends MY_Controller {

	protected $_ttl_cache = 86400;
	protected $_endpoint_list = array (
					'translate_to_pinyin' => '/map/chiense_pinyin/translate/{token}/{text}{/char_type:hans_or_hant}{/accent:yes_or_no}',
				);

	public function index () {
		$this->output->set_content_type('application/json; charset=utf-8');
		$data['usage'] = $this->_endpoint_list;
		$this->load->view('usage', $data);
	}

	public function translate ($token, $text, $char_type = 'hans', $accent = 'yes') {
		$endpoint_name = 'translate_to_pinyin';

		if (!isset ($token) || !isset($text)) {
			return;
		}

		if (!$this->_check_callback ($data)) {
			return;
		}

		if (!$this->_check_token ($token, $endpoint_name, "$text ($char_type : $accent)")) {
			return;
		}

		$this->load->driver ('cache', array('adapter' => 'apc', 'backup' => 'file'));

		require_once ('application/libraries/pinyin/Pinyin.php');

		$cache_info = array('cache_obj' => $this->cache, 'cache_get_func' => 'my_static_cache_get',
				'cache_set_func' => 'my_static_cache_set');
		\Overtrue\Pinyin\Pinyin::set_real_cache ($cache_info);

		if ($char_type == 'hant') {
			\Overtrue\Pinyin\Pinyin::set ('traditional', true);
		}
		if ($accent == 'no') {
			\Overtrue\Pinyin\Pinyin::set ('accent', false);
		}
		$data['message'] = \Overtrue\Pinyin\Pinyin::pinyin (urldecode ($text));
		$data['endpoint'] = $this->_endpoint_list[$endpoint_name];
		$this->load->view('message', $data);
	}

}

/* End of file chinese_converter.php */

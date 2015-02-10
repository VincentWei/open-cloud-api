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

if (!function_exists ('my_static_cache_get')) {
	function my_static_cache_get ($cache_obj, $key) {
		if (is_object ($cache_obj)) {
			return $cache_obj->get ($key);
		}
		return FALSE;
	}
}

if (!function_exists ('my_static_cache_set')) {
	function my_static_cache_set ($cache_obj, $key, $data, $ttl) {
		if (is_object ($cache_obj)) {
			$cache_obj->save ($key, $data, $ttl);
		}
	}
}

class MY_Controller extends CI_Controller {

	function __construct () {
		parent::__construct ();
	}

	protected function _check_callback (&$data) {
		if (isset ($_GET['callback'])) {
			$data['callback'] = $_GET['callback'];
			if (!preg_match ("/^[a-zA-Z][a-zA-Z0-9_]*$/", $data['callback'])) {
				show_error ('Bad callback parameter.', 400);
				return FALSE;
			}
		}

		if (isset ($data['callback'])) {
			$this->output->set_content_type('application/javascript; charset=utf-8');
		}
		else {
			$this->output->set_content_type('application/json; charset=utf-8');
		}
		return TRUE;
	}

	protected function _check_token ($token, $endpoint, $param) {
		if (!preg_match ("/^[a-z0-9]{64}$/", $token)) {
			show_error ('Bad access token.', 400);
			return FALSE;
		}

		$this->load->model ('Access_token_model', '', TRUE);
		switch ($this->Access_token_model->validate_and_log_access ($token, $endpoint, $param)) {
		case Access_token_model::ERR_NO_SUCH_TOKEN:
			show_error ('No such access token.', 400);
			return FALSE;
		case Access_token_model::ERR_FILE_SYSTEM:
			show_error ('Internal server error.', 500);
			return FALSE;
		case Access_token_model::ERR_TOKEN_EXPIRED:
			show_error ('Token expired.', 403);
			return FALSE;
		case Access_token_model::ERR_TOO_FAST:
			show_error ('Too fast request.', 403);
			return FALSE;
		}

		return TRUE;
	}
}

/* End of file MY_Controller.php */

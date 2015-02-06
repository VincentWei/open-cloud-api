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

class Divisions extends CI_Controller {

	protected $_ttl_cache = 86400;
	protected $_endpoint_list = array (
					'get_division_list' => '/list/divisions/items/{token}/{division_id}{/locale}',
					'get_division_detail' => '/list/divisions/item_detail/{token}/{division_id}',
				);

	public function index () {
		$this->output->set_content_type('application/json; charset=utf-8');
		$data['usage'] = $this->_endpoint_list;
		$this->load->view('usage', $data);
	}

	protected function _check_callback (&$data) {
		if (isset ($_GET['callback'])) {
			$data['callback'] = $_GET['callback'];
			if (!preg_match ("/^[a-zA-Z][a-zA-Z0-9_]*$/", $data['callback'])) {
				show_error ('Bad callback parameter.', 400);
				return FALSE;
			}
		}

		return TRUE;
	}

	protected function _check_token ($token, $endpoint, $param) {
		if (!preg_match ("/^[a-z0-9]{32}$/", $token)) {
			show_error ('Bad access token.', 400);
			return FALSE;
		}

		$this->load->model ('Access_token_model');
		switch ($this->Access_token_model->log_access ($token, $endpoint, $param)) {
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

	public function items ($token, $division_id, $locale = '') {
		$endpoint_name = 'get_division_list';
		if (!isset ($token) || !isset ($division_id)) {
			return;
		}

		$division_id = (int)$division_id;
		if ($division_id <= 0) {
			show_error ('Bad division identifier.', 400);
			return;
		}

		if (!$this->_check_callback ($data)) {
			return;
		}

		if (!$this->_check_token ($token, $endpoint_name, $division_id . ', ' . $locale)) {
			return;
		}

		if ($division_id <= 1024) {
			$min_subdivision_id = $division_id * 1024;
			$max_subdivision_id = $min_subdivision_id + 256;
		}
		else {
			$min_subdivision_id = $division_id * 256;
			$max_subdivision_id = $min_subdivision_id + 256;
		}

		$data['message'] = 'Loaded from cache.';
		$this->load->driver ('cache', array('adapter' => 'apc', 'backup' => 'file'));
		if (preg_match ("/^[a-z][a-z]_[A-Z][A-Z]$/", $locale)) {
			$result = $this->cache->get ("$endpoint_name-$division_id-$locale");
			if ($result === FALSE) {
				$data['message'] = 'Loaded from database.';

				$this->load->database ();
				$sql = 'SELECT A.division_id, B.localized_name AS name
	FROM api_country_divisions AS A, api_country_division_localized_names AS B
	WHERE A.division_id > ? AND A.division_id < ? AND B.division_id=A.division_id AND B.locale=?
	ORDER BY A.division_id';
				$query = $this->db->query ($sql, array ($min_subdivision_id, $max_subdivision_id, $locale));

				if ($query->num_rows() == 0) {
					/* try with language */
					$lang = substr ($locale, 0, 2);
					$query = $this->db->query ($sql, array ($min_subdivision_id, $max_subdivision_id, $lang));
				}
				$result = $query->result_array ();

				$this->cache->save ("$endpoint_name-$division_id-$locale", $result, $this->_ttl_cache);
			}
		}
		else if (preg_match ("/^[a-z][a-z]$/", $locale)) {
			$result = $this->cache->get ("$endpoint_name-$division_id-$locale");
			if ($result === FALSE) {
				$data['message'] = 'Loaded from database.';

				$this->load->database ();
				$sql = 'SELECT A.division_id, B.localized_name AS name
	FROM api_country_divisions AS A, api_country_division_localized_names AS B
	WHERE A.division_id > ? AND A.division_id < ? AND B.division_id=A.division_id AND B.locale=?
	ORDER BY A.division_id';
				$query = $this->db->query ($sql, array ($min_subdivision_id, $max_subdivision_id, $locale));
				$result = $query->result_array ();

				$this->cache->save ("$endpoint_name-$division_id-$locale", $result, $this->_ttl_cache);
			}
		}
		else if ($locale == '') {
			$result = $this->cache->get ("$endpoint_name-$division_id-default");
			if ($result === FALSE) {
				$data['message'] = 'Loaded from database.';

				$this->load->database ();
				$sql= 'SELECT division_id, name FROM api_country_divisions
	WHERE division_id > ? AND division_id < ? ORDER BY division_id';
				$query = $this->db->query ($sql, array ($min_subdivision_id, $max_subdivision_id));
				$result = $query->result_array ();
				$this->cache->save ("$endpoint_name-$division_id-default", $result, $this->_ttl_cache);
			}
		}
		else {
			show_error ('Bad locale.', 400);
			return;
		}

		if (isset ($data['callback'])) {
			$this->output->set_content_type('application/javascript; charset=utf-8');
		}
		else {
			$this->output->set_content_type('application/json; charset=utf-8');
		}
		$data['items'] = $result;
		$data['endpoint'] = $this->_endpoint_list[$endpoint_name];
		$this->load->view('list', $data);
	}

	public function item_detail ($token, $division_id) {
		$endpoint_name = 'get_division_detail';

		if (!isset ($token) || !isset ($division_id)) {
			return;
		}

		$division_id = (int)$division_id;
		if ($division_id <= 1024) {
			show_error ('Bad division identifier.', 400);
			return;
		}

		if (!$this->_check_callback ($data)) {
			return;
		}

		if (!$this->_check_token ($token, $endpoint_name, $division_id)) {
			return;
		}

		$data['message'] = 'Loaded from cache.';
		$this->load->driver ('cache', array('adapter' => 'apc', 'backup' => 'file'));
		$data['items'] = $this->cache->get ("$endpoint_name-$division_id-items");
		if ($data['items'] === FALSE) {
			$data['message'] = 'Loaded from database.';

			$this->load->database ();
			$sql = 'SELECT division_id, name, locale, adm_code
	FROM api_country_divisions WHERE division_id=?';
			$query = $this->db->query ($sql, array ($division_id));
			$data['items'] = $query->row_array (0);

			$this->cache->save ("$endpoint_name-$division_id-items", $data['items'], $this->_ttl_cache);

			if ($query->num_rows() > 0) {
				$sql = 'SELECT locale, localized_name FROM api_country_division_localized_names
	WHERE division_id=? ORDER BY locale';
				$query = $this->db->query ($sql, array ($division_id));
				$data['extras'] = $query->result_array ();

				$this->cache->save ("$endpoint_name-$division_id-extras", $data['extras'], $this->_ttl_cache);
			}
		}
		else {
			$data['extras'] = $this->cache->get ("$endpoint_name-$division_id-extras");
			if ($data['extras'] === FALSE) {
				unset ($data['extras']);
			}
		}

		if (isset ($data['callback'])) {
			$this->output->set_content_type('application/javascript; charset=utf-8');
		}
		else {
			$this->output->set_content_type('application/json; charset=utf-8');
		}
		$data['endpoint'] = $this->_endpoint_list[$endpoint_name];
		$this->load->view('list', $data);
	}
}

/* End of file divisions.php */

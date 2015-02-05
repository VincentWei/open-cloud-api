<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Languages extends CI_Controller {

	protected $_ttl_cache = 86400;

	protected $_endpoint_list = array (
					'get_language_list' => '/list/languages/items/{token}{/locale}',
					'get_language_detail' => '/list/languages/item_detail/{token}/{iso_639_1_code}',
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

	public function items ($token, $locale = '') {
		$endpoint_name = 'get_language_list';

		if (!isset ($token)) {
			return;
		}

		if (!$this->_check_callback ($data)) {
			return;
		}

		if (!$this->_check_token ($token, $endpoint_name, $locale)) {
			return;
		}

		$data['message'] = 'Loaded from cache.';
		$this->load->driver ('cache', array('adapter' => 'apc', 'backup' => 'file'));
		if (preg_match ("/^[a-z][a-z]_[A-Z][A-Z]$/", $locale)) {
			$result = $this->cache->get ("$endpoint_name-$locale");
			if ($result === FALSE) {
				$data['message'] = 'Loaded from database.';

				$this->load->database ();
				$sql = 'SELECT A.iso_639_1_code, B.localized_name AS name
	FROM api_languages AS A, api_language_localized_names AS B
	WHERE A.iso_639_1_code=B.iso_639_1_code AND B.locale=?
	ORDER BY A.iso_639_1_code';
				$query = $this->db->query ($sql, array ($locale));

				if ($query->num_rows() == 0) {
					/* try with language */
					$lang = substr ($locale, 0, 2);
					$sql = 'SELECT A.iso_639_1_code, B.localized_name AS name
	FROM api_languages AS A, api_language_localized_names AS B
	WHERE A.iso_639_1_code=B.iso_639_1_code AND B.locale=?
	ORDER BY A.iso_639_1_code';
					$query = $this->db->query ($sql, array ($lang));
				}
				$result = $query->result_array ();

				$this->cache->save ("$endpoint_name-$locale", $result, $this->_ttl_cache);
			}
		}
		else if (preg_match ("/^[a-z][a-z]$/", $locale)) {
			$result = $this->cache->get ("$endpoint_name-$locale");
			if ($result === FALSE) {
				$data['message'] = 'Loaded from database.';

				$this->load->database ();
				$sql = 'SELECT A.iso_639_1_code, B.localized_name AS name
	FROM api_languages AS A, api_language_localized_names AS B
	WHERE A.iso_639_1_code=B.iso_639_1_code AND B.locale=?
	ORDER BY A.iso_639_1_code';
				$query = $this->db->query ($sql, array ($locale));
				$result = $query->result_array ();

				$this->cache->save ("$endpoint_name-$locale", $result, $this->_ttl_cache);
			}
		}
		else if ($locale == '') {
			$result = $this->cache->get ("$endpoint_name-default");
			if ($result === FALSE) {
				$data['message'] = 'Loaded from database.';

				$this->load->database ();
				$sql = 'SELECT iso_639_1_code, self_name AS name FROM api_languages ORDER BY iso_639_1_code';
				$query = $this->db->query ($sql);
				$result = $query->result_array ();

				$this->cache->save ("$endpoint_name-default", $result, $this->_ttl_cache);
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

	public function item_detail ($token, $iso_639_1_code) {
		$endpoint_name = 'get_language_detail';
		if (!isset ($token) || !isset ($iso_639_1_code)) {
			return;
		}

		if (!preg_match ("/^[a-z]{2}$/", $iso_639_1_code)) {
			show_error ('Bad ISO 639-1 code of language.', 400);
			return;
		}

		if (!$this->_check_callback ($data)) {
			return;
		}

		if (!$this->_check_token ($token, $endpoint_name, $iso_639_1_code)) {
			return;
		}

		$data['message'] = 'Loaded from cache.';
		$this->load->driver ('cache', array('adapter' => 'apc', 'backup' => 'file'));
		$data['items'] = $this->cache->get ("$endpoint_name-$iso_639_1_code-items");
		if ($data['items'] === FALSE) {
			$data['message'] = 'Loaded from database.';

			$this->load->database ();
			$sql = 'SELECT iso_639_1_code, iso_639_2_b_code, iso_639_2_t_code, iso_639_3_code, self_name AS name
	FROM api_languages WHERE iso_639_1_code=?';
			$query = $this->db->query ($sql, array ($iso_639_1_code));
			$data['items'] = $query->row_array (0);

			$this->cache->save ("$endpoint_name-$iso_639_1_code-items", $data['items'], $this->_ttl_cache);

			if ($query->num_rows() > 0) {
				$sql = 'SELECT locale, localized_name FROM api_language_localized_names
	WHERE iso_639_1_code=? ORDER BY locale';
				$query = $this->db->query ($sql, array ($iso_639_1_code));
				$data['extras'] = $query->result_array ();
				$this->cache->save ("$endpoint_name-$iso_639_1_code-extras", $data['extras'], $this->_ttl_cache);
			}
		}
		else {
			$data['extras'] = $this->cache->get ("$endpoint_name-$iso_639_1_code-extras");
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

/* End of file languages.php */

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

class Access_token_model extends CI_Model {

	const TTL   = 36000;		// Time to live: 36000 seconds (10 hours)
	const TTNT  = 18000;		// Time to next token: 18000 seconds (5 hours)
	const MTTNA = 10;			// Micro-Time to next access: 100 micro-seconds

	const OK                   = 0;

	const ERR_NO_SUCH_APP_KEY  = 1;
	const ERR_FILE_SYSTEM      = 2;
	const ERR_NO_SUCH_TOKEN	   = 3;
	const ERR_TOKEN_EXPIRED    = 4;
	const ERR_TOO_FAST         = 5;

	const ERR_GENERIC          = 100;

	protected $_cache_path;

	public function __construct() {
		// Call the Model constructor
		parent::__construct();

		// use cache_path to save the token information
		$path = $this->config->item('cache_path');
		$this->_cache_path = ($path == '') ? APPPATH.'cache/' : $path;
	}

	protected function _get_app_log_file_path ($app_key) {
		$full_dir = $this->_cache_path;
		$full_dir .= $app_key[0] . $app_key[1] . '/';
		$full_dir .= $app_key[2] . $app_key[3] . '/';
		$full_dir .= $app_key[4] . $app_key[5] . '/';
		if (!is_dir ($full_dir)) {
			if (!mkdir ($full_dir, 0775, true)) {
				return FALSE;
			}
		}

		return $full_dir . $app_key;
	}

	public function generate ($app_key, $client_id) {
		$sql = 'SELECT fse_id FROM fse_app_keys WHERE app_key=? AND status=1';
		$query = $this->db->query ($sql, array ($app_key));
		if ($query->num_rows() == 0) {
			return self::ERR_NO_SUCH_APP_KEY;
		}

		$sql = 'SELECT access_token, UNIX_TIMESTAMP(create_time) AS create_timestamp
	FROM fse_app_access_tokens WHERE app_key=? AND client_id=?';
		$query = $this->db->query ($sql, array ($app_key, $client_id));
		if ($query->num_rows() > 0) {
			$row = $query->row ();
			if (time() < $row->create_timestamp + self::TTNT) {
				return $row->access_token;
			}
		}

		$token = hash_hmac ('sha256', $client_id . microtime(), $app_key);
		$sql = 'INSERT INTO fse_app_access_tokens (app_key, client_id, access_token, create_time, access_microtime)
	VALUES (?, ?, ?, NOW(), 0) ON DUPLICATE KEY UPDATE access_token=?, create_time=NOW(), access_microtime=0';
		$this->db->query ($sql, array ($app_key, $client_id, $token, $token));
		if ($this->_get_app_log_file_path ($app_key) === FALSE) {
			return self::ERR_FILE_SYSTEM;
		}

		return $token;
	}

	public function validate_and_log_access ($token, $endpoint, $param = 'na') {
		$sql = 'SELECT app_key, client_id, UNIX_TIMESTAMP(create_time) AS create_timestamp, access_microtime FROM fse_app_access_tokens WHERE access_token=?';
		$query = $this->db->query ($sql, array ($token));
		if ($query->num_rows() == 0) {
			return self::ERR_NO_SUCH_TOKEN;
		}
		$row = $query->row ();

		if (time() >  $row->create_timestamp + self::TTL) {
			return self::ERR_TOKEN_EXPIRED;
		}

		$curr_mtime = (int)(microtime (TRUE) * 1000);
		if (($curr_mtime - $row->access_microtime) < self::MTTNA) {
			return self::ERR_TOO_FAST;
		}

		$sql = 'UPDATE fse_app_access_tokens SET access_microtime=? WHERE access_token=?';
		$query = $this->db->query ($sql, array ($curr_mtime, $token));

		$log_file_path = $this->_get_app_log_file_path ($row->app_key);
		if (!$fp = @fopen ($log_file_path, 'a')) {
			return self::ERR_FILE_SYSTEM;
		}

		$this->load->helper ('misc');

		$client_ip = get_real_ip ();
		$user_agent = isset ($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
		$message = date ('Y-m-d H:i:s') . ' ' . $row->client_id . " ($token)" . "\n\tclient_ip: $client_ip\n\tuser_agent: $user_agent\n\tendpoint: $endpoint ($param)\n";
		flock ($fp, LOCK_EX);
		fwrite ($fp, $message);
		flock ($fp, LOCK_UN);
		fclose ($fp);

		$CI =& get_instance();
		$CI->app_key = $row->app_key;
		return self::OK;
	}
}

/* End of file access_token_model.php */

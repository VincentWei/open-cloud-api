<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Access_token_model extends CI_Model {

	const TTL  = 36000;		// Time to live: 36000 seconds (10 hours)
	const TTNT = 18000;		// Time to next token: 18000 seconds (5 hours)
	const TTNA = 1;			// Time to next access: 1 second

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

	protected function _get_token_path ($token) {
		$full_dir = $this->_cache_path;
		$full_dir .= $token[0] . '/';
		$full_dir .= $token[1] . '/';
		$full_dir .= $token[2] . '/';
		if (!is_dir ($full_dir)) {
			if (!mkdir ($full_dir, 0775, true)) {
				return FALSE;
			}
		}

		return $full_dir . $token;
	}

	public function generate ($app_key) {
		$sql = 'SELECT fse_id, app_name, last_token, UNIX_TIMESTAMP(token_time) AS token_ctime
	FROM fse_app_keys WHERE app_key=? AND status=1';
		$query = $this->db->query ($sql, array ($app_key));
		if ($query->num_rows() == 0) {
			return self::ERR_NO_SUCH_APP_KEY;
		}

		$row = $query->row_array (0);

		if (strlen ($row['last_token']) == 32) {
			// Remove the old token file if no logged message
			$last_token_file = $this->_get_token_path ($row['last_token']);
			clearstatcache ();
			if (file_exists ($last_token_file)) {
				if (time() < $row['token_ctime'] + self::TTNT) {
					return $row['last_token'];
				}
				if (filesize ($last_token_file) < 80)
					unlink ($last_token_file);
			}
		}


		$token = hash_hmac ('md5', $row ['fse_id'] . $row['app_name'] . time(), $app_key);
		$sql = 'UPDATE fse_app_keys SET last_token=?, token_time=NOW() WHERE app_key=?';
		$this->db->query ($sql, array ($token, $app_key));

		if (!$fp = @fopen ($this->_get_token_path ($token), 'w')) {
			return self::ERR_FILE_SYSTEM;
		}

		$ctime = '' . time();
		flock ($fp, LOCK_EX);
		fwrite ($fp, "$ctime\n");
		fwrite ($fp, "$app_key\n");
		flock ($fp, LOCK_UN);
		fclose ($fp);

		return $token;
	}

	public function validate ($token) {
		$token_path = $this->_get_token_path ($token);

		clearstatcache ();
		$stat = stat ($token_path);
		if ($stat === FALSE) {
			return self::ERR_NO_SUCH_TOKEN;
		}

		if (time() >  $stat['ctime'] + self::TTL) {
			if ($stat['size'] < 80)
				unlink ($token_path);
			return self::ERR_TOKEN_EXPIRED;
		}

		return self::OK;
	}

	public function log_access ($token, $endpoint, $param = 'na') {
		$token_path = $this->_get_token_path ($token);

		if (!file_exists ($token_path)) {
			return self::ERR_NO_SUCH_TOKEN;
		}

		if (!$fp = @fopen ($token_path, 'r+')) {
			return self::ERR_FILE_SYSTEM;
		}

		$fstat = fstat ($fp);

		$ctime = trim (fgets($fp));
		if (time() >  $ctime + self::TTL) {
			if ($fstat['size'] < 80)
				unlink ($token_path);
			return self::ERR_TOKEN_EXPIRED;
		}

		/*
		if (time() <  $fstat['mtime'] + self::TTNA) {
			fclose ($fp);
			return self::ERR_TOO_FAST;
		}
		*/

		$this->load->helper ('misc');

		$client_ip = get_real_ip ();
		$user_agent = isset ($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
		$message = date ('Y-m-d H:i:s') . "\n\tclient_ip: $client_ip\n\tuser_agent: $user_agent\n\tendpoint: $endpoint ($param)\n";
		flock ($fp, LOCK_EX);
		fseek ($fp, 0, SEEK_END);
		fwrite ($fp, $message);
		flock ($fp, LOCK_UN);
		fclose ($fp);

		return self::OK;
	}
}

/* End of file access_token_model.php */

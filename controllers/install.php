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

class Install extends CI_Controller {

	private $curr_ver_major = 0;
	private $curr_ver_minor = 2;
	private $curr_ver_micro = 0;

	protected $_endpoint_list = array (
			'create_tables' => '/install/create_tables',
			'create_test_app_key' => '/install/create_test_app_key',
		);

	public function index() {
		$this->output->set_content_type('application/json; charset=utf-8');
		$data['usage'] = $this->_endpoint_list;
		$this->load->view('usage', $data);
	}

	protected function _initialize_database (&$data) {
		if ($this->db->table_exists ('api_version_info') === FALSE) {
			$this->load->dbforge ();
			$this->dbforge->add_field ('token_name varchar(16) NOT NULL');
			$this->dbforge->add_field ('token_value varchar(255) NOT NULL');
			$this->dbforge->add_key ('token_name', TRUE);
			$this->dbforge->create_table ('api_version_info');
			$this->db->query ("INSERT INTO api_version_info VALUES ('curr_ver_major', '0')");
			$this->db->query ("INSERT INTO api_version_info VALUES ('curr_ver_minor', '2')");
			$this->db->query ("INSERT INTO api_version_info VALUES ('curr_ver_micro', '0')");
			$data['message'] .= 'api_version_info created. ';
		}

		if ($this->db->table_exists ('fse_app_keys') === FALSE) {
			$this->load->dbforge ();
			$this->dbforge->add_field ('app_key varchar(64) NOT NULL');
			$this->dbforge->add_field ('fse_id varchar(32) NOT NULL');
			$this->dbforge->add_field ('app_name varchar(32) NOT NULL');
			$this->dbforge->add_field ('app_desc varchar(255) NOT NULL');
			$this->dbforge->add_field ('app_url varchar(255) DEFAULT NULL');
			$this->dbforge->add_field ('app_icon_url varchar(255) DEFAULT NULL');
			$this->dbforge->add_field ('status tinyint(4) NOT NULL DEFAULT \'1\'');
			$this->dbforge->add_field ('create_time datetime NOT NULL');
			$this->dbforge->add_key ('app_key', TRUE);
			$this->dbforge->create_table ('fse_app_keys');
			$this->db->query ("ALTER TABLE fse_app_keys ADD UNIQUE KEY idx_fse_id_app_name (fse_id, app_name)");
			$data['message'] .= 'fse_app_keys created. ';
		}

		if ($this->db->table_exists ('fse_app_access_tokens') === FALSE) {
			$this->load->dbforge ();
			$this->dbforge->add_field ('app_key varchar(64) NOT NULL');
			$this->dbforge->add_field ('client_id varchar(32) NOT NULL');
			$this->dbforge->add_field ('access_token varchar(64) NOT NULL');
			$this->dbforge->add_field ('create_time datetime NOT NULL');
			$this->dbforge->add_field ('access_microtime bigint(20) unsigned NOT NULL');
			$this->dbforge->add_key ('app_key', TRUE);
			$this->dbforge->add_key ('client_id', TRUE);
			$this->dbforge->add_key ('access_microtime');
			$this->dbforge->add_key ('create_time');
			$this->dbforge->create_table ('fse_app_access_tokens');
			$this->db->query ("ALTER TABLE fse_app_access_tokens ADD UNIQUE KEY idx_access_token (access_token)");
			$data['message'] .= 'fse_app_access_tokens created. ';
		}

		if ($this->db->table_exists ('api_country_codes') === FALSE) {
			$this->dbforge->add_field ('numeric_code int(10) unsigned NOT NULL');
			$this->dbforge->add_field ('alpha_2_code varchar(2) NOT NULL');
			$this->dbforge->add_field ('alpha_3_code varchar(3) NOT NULL');
			$this->dbforge->add_field ('iso_name varchar(64) NOT NULL');
			$this->dbforge->add_key ('numeric_code', TRUE);
			$this->dbforge->add_key ('alpha_2_code');
			$this->dbforge->add_key ('alpha_3_code');
			$this->dbforge->create_table ('api_country_codes');
			$data['message'] .= 'api_country_codes created. ';
		}

		if ($this->db->table_exists ('api_country_divisions') === FALSE) {
			$this->dbforge->add_field ('division_id bigint(20) unsigned NOT NULL');
			$this->dbforge->add_field ('locale varchar(6) NOT NULL');
			$this->dbforge->add_field ('name varchar(64) NOT NULL');
			$this->dbforge->add_field ('adm_code varchar(8) DEFAULT NULL');
			$this->dbforge->add_field ('zip_code varchar(8) DEFAULT NULL');
			$this->dbforge->add_field ('trunk_code varchar(8) DEFAULT NULL');
			$this->dbforge->add_field ('note varchar(255) DEFAULT NULL');
			$this->dbforge->add_key ('division_id');
			$this->dbforge->create_table ('api_country_divisions');
			$data['message'] .= 'api_country_divisions created. ';
		}

		if ($this->db->table_exists ('api_country_division_localized_names') === FALSE) {
			$this->dbforge->add_field ('division_id bigint(20) unsigned NOT NULL');
			$this->dbforge->add_field ('locale varchar(6) NOT NULL');
			$this->dbforge->add_field ('localized_name varchar(64) DEFAULT NULL');
			$this->dbforge->add_key ('division_id', TRUE);
			$this->dbforge->add_key ('locale', TRUE);
			$this->dbforge->create_table ('api_country_division_localized_names');
			$data['message'] .= 'api_country_division_localized_names created. ';
		}

		if ($this->db->table_exists ('api_languages') === FALSE) {
			$this->dbforge->add_field ('iso_639_1_code varchar(2) NOT NULL');
			$this->dbforge->add_field ('iso_639_2_b_code varchar(3) DEFAULT NULL');
			$this->dbforge->add_field ('iso_639_2_t_code varchar(3) DEFAULT NULL');
			$this->dbforge->add_field ('iso_639_3_code varchar(3) DEFAULT NULL');
			$this->dbforge->add_field ('self_name varchar(64) NOT NULL');
			$this->dbforge->add_field ('note varchar(255) DEFAULT NULL');
			$this->dbforge->add_key ('iso_639_1_code', TRUE);
			$this->dbforge->add_key ('iso_639_2_b_code');
			$this->dbforge->add_key ('iso_639_3_code');
			$this->dbforge->create_table ('api_languages');
			$data['message'] .= 'api_languages created. ';
		}

		if ($this->db->table_exists ('api_language_localized_names') === FALSE) {
			$this->dbforge->add_field ('iso_639_1_code varchar(2) NOT NULL');
			$this->dbforge->add_field ('locale varchar(6) NOT NULL');
			$this->dbforge->add_field ('localized_name varchar(64) DEFAULT NULL');
			$this->dbforge->add_key ('iso_639_1_code', TRUE);
			$this->dbforge->add_key ('locale', TRUE);
			$this->dbforge->create_table ('api_language_localized_names');
			$data['message'] .= 'api_language_localized_names created. ';
		}

	}

	protected function _upgrade_database ($db_ver_info, &$data) {
		if ($db_ver_info['curr_ver_major'] == $this->curr_ver_major
				&& $db_ver_info['curr_ver_minor'] == $this->curr_ver_minor
				&& $db_ver_info['curr_ver_micro'] == $this->curr_ver_micro) {
			return;
		}
	}

	public function create_tables () {
		$this->load->database ();
		if ($this->db === FALSE) {
			show_error ('Failed to initialize database.', 500);
			return;
		}

		$data['message'] = '';
		if ($this->db->table_exists ('api_version_info') === FALSE) {
			$this->_initialize_database ($data);
		}
		else {
			$sql = 'SELECT token_name, token_value FROM api_version_info WHERE token_name LIKE \'curr_ver_%\'';
			$query = $this->db->query ($sql);
			if ($query->num_rows() > 0) {
				$db_ver_info = array ();
				foreach ($query->result() as $row) {
					$db_ver_info[$row->token_name] = $row->token_value;
				}
				$this->_upgrade_database ($db_ver_info, $data);
			}
			else {
				show_error ('No current version information in database .', 500);
				return;
			}
		}

		if (strlen ($data['message']) == 0) {
			$data['message'] = 'No any table created or upgraded.';
		}
		$this->output->set_content_type ('application/json; charset=utf-8');
		$data['endpoint'] = $this->_endpoint_list['create_tables'];
		$this->load->view ('message', $data);
	}

	public function create_test_app_key () {
		$this->load->database ();
		if ($this->db === FALSE) {
			show_error ('Failed to initialize database.', 500);
			return;
		}

		$app_key = hash_hmac ('sha256', 'test', 'nobody');
		$res = $this->db->query ('INSERT IGNORE fse_app_keys
	(app_key, fse_id, app_name, app_desc, app_url, app_icon_url, create_time) VALUES (?, ?, ?, ?, ?, ?, NOW())',
			array ($app_key, 'nobody' , 'Test', 'This is a test app.', NULL, NULL));
		if ($this->db->affected_rows() == 0) {
			$data['message'] = 'Already created!';
		}
		else {
			$data['message'] = $app_key;
		}

		$this->output->set_content_type ('application/json; charset=utf-8');
		$data['endpoint'] = $this->_endpoint_list['create_test_app_key'];
		$this->load->view ('message', $data);
	}
}

/* End of file access_token.php */

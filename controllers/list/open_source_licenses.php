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

class Open_Source_Licenses extends CI_Controller {

	protected $_endpoint_list = array (
					'get_open_source_license_list' => '/list/open_source_licenses/items',
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

	public function items() {
		if (!$this->_check_callback ($data)) {
			return;
		}

		$items = array (
				array ('short_name' => 'gpl-2.0', 'name' => 'GNU General Public License (GPL-2.0)'),
				array ('short_name' => 'gpl-3.0', 'name' => 'GNU General Public License 3.0 (GPL-3.0)'),
				array ('short_name' => 'lgpl-2.1', 'name' => 'GNU Library or "Lesser" General Public License 2.1 (LGPL-2.1)'),
				array ('short_name' => 'lgpl-3.0', 'name' => 'GNU Library or "Lesser" General Public License 3.0 (LGPL-3.0)'),
				array ('short_name' => 'agpl-3.0', 'name' => 'GNU Affero General Public License v3 (AGPL-3.0)'),

				array ('short_name' => 'apache-2.0', 'name' => 'Apache License 2.0 (Apache-2.0)'),
				array ('short_name' => 'mpl-2.0', 'name' => 'Mozilla Public License 2.0 (MPL-2.0)'),
				array ('short_name' => 'php-3.0', 'name' => 'PHP License 3.0 (PHP-3.0)'),
				array ('short_name' => 'epl-1.0', 'name' => 'Eclipse Public License 1.0 (EPL-1.0)'),
				array ('short_name' => 'postgresql', 'name' => 'The PostgreSQL License (PostgreSQL)'),
				array ('short_name' => 'python-2.0', 'name' => 'Python License (Python-2.0)'),
				array ('short_name' => 'xnet', 'name' => 'X.Net License (Xnet)'),
				array ('short_name' => 'w3c', 'name' => 'W3C License (W3C)'),
				array ('short_name' => 'zope', 'name' => 'Zope Public License 2.0 (ZPL-2.0)'),
				array ('short_name' => 'zlib', 'name' => 'zlib/libpng license (Zlib)'),
				array ('short_name' => 'lppl-1.3c', 'name' => 'LaTeX Project Public License 1.3c (LPPL-1.3c)'),
				array ('short_name' => 'qpl-1.0', 'name' => 'Qt Public License (QPL-1.0)'),
				array ('short_name' => 'wxwindows', 'name' => 'wxWindows Library License (WXwindows)'),

				array ('short_name' => 'osl-3.0', 'name' => 'Open Software License 3.0 (OSL-3.0)'),
				array ('short_name' => 'ofl-1.1', 'name' => 'Open Font License 1.1 (OFL-1.1)'),
				array ('short_name' => 'ecl-2.0', 'name' => 'Educational Community License, Version 2.0 (ECL-2.0)'),
				array ('short_name' => 'apl-3.0', 'name' => 'Academic Free License 3.0 (AFL-3.0)'),
				array ('short_name' => 'apl-1.0', 'name' => 'Adaptive Public License (APL-1.0)'),
				array ('short_name' => 'artistic-2.0', 'name' => 'Artistic license 2.0 (Artistic-2.0)'),

				array ('short_name' => 'bsd-3-clause', 'name' => 'BSD 3-Clause "New" or "Revised" License (BSD-3-Clause)'),
				array ('short_name' => 'bsd-2-clause', 'name' => 'BSD 2-Clause "Simplified" or "FreeBSD" License (BSD-2-Clause)'),
				array ('short_name' => 'mit', 'name' => 'MIT License (MIT)'),
				array ('short_name' => 'ncsa', 'name' => 'University of Illinois/NCSA Open Source License (NCSA)'),

				array ('short_name' => 'nasa-1.3', 'name' => 'NASA Open Source Agreement 1.3 (NASA-1.3)'),
				array ('short_name' => 'ipl-1.0', 'name' => 'IBM Public License 1.0 (IPL-1.0)'),
				array ('short_name' => 'spl-1.0', 'name' => 'Sun Public License 1.0 (SPL-1.0)'),
				array ('short_name' => 'apsl-2.0', 'name' => 'Apple Public Source License (APSL-2.0)'),
				array ('short_name' => 'ms-pl', 'name' => 'Microsoft Public License (MS-PL)'),
				array ('short_name' => 'public', 'name' => 'Public Domain'),
			);

		if (isset ($data['callback'])) {
			$this->output->set_content_type('application/javascript; charset=utf-8');
		}
		else {
			$this->output->set_content_type('application/json; charset=utf-8');
		}

		$data['items'] = $items;
		$data['endpoint'] = $this->_endpoint_list['get_open_source_license_list'];
		$this->load->view ('list', $data);
	}
}

/* End of file open_source_licenses.php */

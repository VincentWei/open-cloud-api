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

class Computer_Languages extends CI_Controller {

	protected $_endpoint_list = array (
					'get_computer_language_list' => '/list/computer_languages/items',
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
				array ('short_name' => 'actionscript3', 'name' => 'ActionScript 3'),
				array ('short_name' => 'ada', 'name' => 'Ada'),
				array ('short_name' => 'awk', 'name' => 'Awk'),
				array ('short_name' => 'applescript', 'name' => 'Applescript'),
				array ('short_name' => 'asm', 'name' => 'Asm'),
				array ('short_name' => 'bash', 'name' => 'Bash/shell'),
				array ('short_name' => 'bison', 'name' => 'Bison'),
				array ('short_name' => 'coldfusion', 'name' => 'ColdFusion'),
				array ('short_name' => 'csharp', 'name' => 'C#'),
				array ('short_name' => 'cpp', 'name' => 'C/C++'),
				array ('short_name' => 'cobol', 'name' => 'Cobol'),
				array ('short_name' => 'css', 'name' => 'CSS'),
				array ('short_name' => 'd', 'name' => 'D'),
				array ('short_name' => 'emacs-lisp', 'name' => 'Emacs Lisp'),
				array ('short_name' => 'diff', 'name' => 'Diff/Patch'),
				array ('short_name' => 'erlang', 'name' => 'Erlang'),
				array ('short_name' => 'go', 'name' => 'Go'),
				array ('short_name' => 'groovy', 'name' => 'Groovy'),
				array ('short_name' => 'html', 'name' => 'HTML'),
				array ('short_name' => 'islisp', 'name' => 'IsLisp'),
				array ('short_name' => 'javascript', 'name' => 'JavaScript'),
				array ('short_name' => 'java', 'name' => 'Java'),
				array ('short_name' => 'javafx', 'name' => 'JavaFX'),
				array ('short_name' => 'lilypond', 'name' => 'Lilypond'),
				array ('short_name' => 'lisp', 'name' => 'Lisp'),
				array ('short_name' => 'lua', 'name' => 'Lua'),
				array ('short_name' => 'latex', 'name' => 'Latex'),
				array ('short_name' => 'pascal', 'name' => 'Pascal'),
				array ('short_name' => 'perl', 'name' => 'Perl'),
				array ('short_name' => 'php', 'name' => 'PHP'),
				array ('short_name' => 'plain', 'name' => 'Plain Text'),
				array ('short_name' => 'po', 'name' => 'Po'),
				array ('short_name' => 'powershell', 'name' => 'PowerShell'),
				array ('short_name' => 'postscript', 'name' => 'Postscript'),
				array ('short_name' => 'prolog', 'name' => 'Prolog'),
				array ('short_name' => 'python', 'name' => 'Python'),
				array ('short_name' => 'r', 'name' => 'R statistics programming language'),
				array ('short_name' => 'ruby', 'name' => 'Ruby'),
				array ('short_name' => 'scala', 'name' => 'Scala'),
				array ('short_name' => 'scheme', 'name' => 'Scheme'),
				array ('short_name' => 'sql', 'name' => 'SQL'),
				array ('short_name' => 'tcl', 'name' => 'Tcl'),
				array ('short_name' => 'vb', 'name' => 'Visual Basic'),
				array ('short_name' => 'xml', 'name' => 'XML'),
			);

		if (isset ($data['callback'])) {
			$this->output->set_content_type('application/javascript; charset=utf-8');
		}
		else {
			$this->output->set_content_type('application/json; charset=utf-8');
		}

		$data['items'] = $items;
		$data['endpoint'] = $this->_endpoint_list['get_computer_language_list'];
		$this->load->view('list', $data);
	}
}

/* End of file computer_languages.php */

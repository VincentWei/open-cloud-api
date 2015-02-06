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

function get_real_ip ()
{
	if (isset ($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$client_ip = isset ($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';

		$entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
		reset ($entries);
		while (list(, $entry) = each ($entries)) {
			$entry = trim ($entry);
			if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list)) {
				// http://www.faqs.org/rfcs/rfc1918.html
				$private_ip = array(
						'/^0\./',
						'/^127\.0\.0\.1/',
						'/^192\.168\..*/',
						'/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
						'/^10\..*/');

				$found_ip = preg_replace ($private_ip, $client_ip, $ip_list[1]);

				if ($client_ip != $found_ip) {
					$client_ip = $found_ip;
					break;
				}
			}
		}
	}
	else {
		$client_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
	}

	return $client_ip;
}

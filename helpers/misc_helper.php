<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

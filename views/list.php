<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$ret_info = array ();
$ret_info['status'] = isset($status) ? $status : 'ok';
$ret_info['message'] = isset($message) ? $message : 'no additional message';
$ret_info['endpoint'] = $endpoint;
$ret_info['items'] = $items;
$ret_info['extras'] = isset($extras) ? $extras : array();
$ret_info['elapsed_time'] = '{elapsed_time}';
$ret_info['memory_usag'] = '{memory_usage}';

if (isset ($callback)) {
	echo "/**/$callback(";
}

echo json_encode ($ret_info, JSON_BIGINT_AS_STRING | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

if (isset ($callback)) {
	echo ')';
}

echo PHP_EOL;

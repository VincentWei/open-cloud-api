<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$ret_info = array ();
$ret_info['status'] = 'ok';
$ret_info['message'] = $message;
$ret_info['endpoint'] = $endpoint;
$ret_info['elapsed_time'] = '{elapsed_time}';
$ret_info['memory_usage'] = '{memory_usage}';

echo json_encode ($ret_info, JSON_BIGINT_AS_STRING | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
echo PHP_EOL;

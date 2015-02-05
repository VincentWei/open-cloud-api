<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$ret_info = array ();
$ret_info['status'] = 'error';
$ret_info['which'] = 'database';
$ret_info['heading'] = $heading;
$ret_info['message'] = strip_tags ($message);

echo json_encode ($ret_info, JSON_BIGINT_AS_STRING | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
echo PHP_EOL;

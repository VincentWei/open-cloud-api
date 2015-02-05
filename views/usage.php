<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

echo json_encode ($usage, JSON_BIGINT_AS_STRING | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
echo PHP_EOL;

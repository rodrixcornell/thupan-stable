<?php

$_SERVER['DOCUMENT_ROOT']   = __DIR__ . '/../../';

foreach($_SERVER as $key => $value) {
    !defined($key) && is_scalar($value) ? define($key, $value) : false;
}

(Bool)  $_env    = getEnvironment();
(Array) $_config = getConfig();

foreach($_config['app'] as $key => $value) {
    $value = getenv($key) ? getenv($key) : $value;
    !defined($key) && is_scalar($value) ? define( (String) $key, $value) : false;
}

!defined('DS')       ? define('DS', DIRECTORY_SEPARATOR) : false;
!defined('DOC_ROOT') ? define('DOC_ROOT', DOCUMENT_ROOT) : false;

if($file = getFilePath($_path['app.support.tools'])) {
    require (String) $file;
}

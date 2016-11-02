<?php

$_path['app.config.dir']        = __DIR__ . '../Config/';
$_path['app.bootstrap']         = __DIR__ . '/bootstrap.php';
$_path['app.support.tools']     = __DIR__ . '/../Support/tools.php';
$_path['composer']              = __DIR__ . '/../../vendor/autoload.php';
$_path['thupan.support.tools']  = __DIR__ . '/../../vendor/thupan/framework/src/Support/tools.php';

if(file_exists($_path['thupan.support.tools'])) {
    require_once (String) $_path['thupan.support.tools'];

    if($file = getFilePath($_path['composer'])) {
        require_once (String) $file;
    } else {
        die('Composer não foi encontrado.');
    }

    if($file = getFilePath($_path['app.bootstrap'])) {
        require_once (String) $file;
    } else {
        die('Bootstrap não foi encontrado.');
    }
} else {
    die('Thupan Framework não foi encontrado.');
}

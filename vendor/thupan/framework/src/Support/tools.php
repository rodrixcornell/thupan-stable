<?php

!defined('PROJECT_DIR') ? define('PROJECT_DIR', __DIR__ . '/../../../../../') : false;

// garante que o path do arquivo passado existe e retorna o path completo
function getFilePath($file = null) {
    return file_exists($file) ? $file : false;
}

// pegar todos os arquivos de configuracoes ou apenas o informado
function getConfig($file = null) {
    (Array) $configure = [];

    if(!$file) {
        foreach(glob(PROJECT_DIR . 'app/Config/*.php') as $file) {
            if($file = getFilePath($file)) {
                (Array)  $key = explode('/',$file);
                (String) $key = strtolower(str_replace('.php', '', end($key)));
                (Array)  $configure[$key] = require_once $file;
            }
        }
    } else {
        if($file = getFilePath(PROJECT_DIR . 'app/Config/' . strtolower($file) . '.php')) {
            (Array)  $key = explode('/',$file);
            (String) $key = strtolower(str_replace('.php', '', end($key)));
            (Array)  $configure[$key] = require_once $file;
        }
    }

    return ($configure) ? $configure : [];
}

// verifica se foi criado o .env, entao cria as variaveis de ambiente.
function getEnvironment() {
    if($file = getFilePath(PROJECT_DIR . '.env')) {
        (String) $env = file_get_contents($file);
        (Array)  $env = explode("\n", $env);

        foreach($env as $index => $variable) {
            if($variable) {
                putenv( (String) $variable);
            }
        }
    } else {
        return (Bool) false;
    }

    return (Bool) true;
}

function getRoutes() {

}

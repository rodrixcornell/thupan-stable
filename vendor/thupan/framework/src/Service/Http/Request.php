<?php

namespace Service\Http;

class Request {
    protected static $_detectors   = [];
    protected static $_controller  = null;
    protected static $_method      = null;
    protected static $_args        = [];
    protected static $_http_method = null;
    protected static $_uri         = null;

    public static function getController() {
        return self::$_controller;
    }

    public static function getMethod() {
        return self::$_method;
    }

    public static function getArgs() {
        return self::$_args;
    }

    public static function getUri() {
        return self::$_uri;
    }

    public static function uri() {
        $parts = explode('/', $_SERVER['PHP_SELF']);

        $index_pos = array_search("index.php", $parts);

        if ($index_pos) {
            for ($i = 0; $i <= $index_pos; $i++) {
                unset($parts[$i]);
            }
        }

        $parts = array_filter($parts);

        self::$_uri        = implode('/', $parts);
        self::$_controller = array_shift($parts);
        self::$_method     = array_shift($parts);
        self::$_args       = isset($parts) ? $parts : [];
    }

    /* adiciona um novo tipo de requisicao para validar */
    public static function addDetector($name, $callback) {
        self::$_detectors[$name] = $callback;
    }

    /* verifica qual tipo de requisicao é */
    public static function is($name) {

        $ajax    = ($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : false;
        $request = ($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : false;

        switch($name) {
            case 'ajax':    return (!empty($ajax) && ($ajax === 'xmlhttprequest')); break;
            case 'get':     return (!empty($request) && ($request) === 'get'); break;
            case 'post':    return (!empty($request) && ($request) === 'post'); break;
            case 'put':     return false; break;
            case 'delete':  return false; break;
            case 'head':    return false; break;
            case 'options': return false; break;
            case 'ssl':     return false; break;
            case 'flash':   return false; break;
            case 'mobile':  return false; break;
            default:
                if(in_array($name, array_keys(self::$_detectors))) {
                    if(is_callable(self::$_detectors[$name])) {
                        return call_user_func(self::$_detectors[$name]);
                    } else {
                        return self::$_detectors[$name];
                    }
                }
        }
    }

    /* Permite você acessar quaisquer HTTP_* da requisição. */
    public static function header($name) {
        foreach($_SERVER as $key => $value) {
            $key = strtolower(str_replace('_', '-', (str_replace('HTTP_', '', $key))));
            if($key === $name) {
                return $value;
            }
        }
    }

    /* Descobre o tipo de conteudo do cliente ou verifica se existe um */
    public static function accepts($name = null) {
        if(!is_null) {
            return in_array($name, explode(',', self::$header('accept'))) ? true : false;
        } else {
            return explode(',', self::$header('accept'));
        }
    }
}

<?php

namespace Routing;

use \Service\Http\Request;
use \Service\Http\Response;
use \Service\Redirect;
use \Routing\Filter;

class Router {
    // array para rotas
    protected static $routes    = [];
    // array para metodos estaticos
    protected static $methods   = [];
    // array para callbacks
    protected static $callbacks = [];

    // habilita/desabilita autoDispatch por padrão
    public static $autoDispatch = true;

    // array para passagem de argumentos nas callbacks
    protected static $patterns = [
       ':any'    => '[^/]+',
       ':num'    => '-?[0-9]+',
       ':all'    => '.*',
       ':hex'    => '[[:xdigit:]]+',
       ':uuidV4' => '\w{8}-\w{4}-\w{4}-\w{4}-\w{12}',
    ];

    public static function __callstatic($method, $params) {
        // adiciona o metodo passado estaticamente em um conjunto de array.
        array_push(self::$methods,   strtoupper($method));
        // adiciona as rotas passado estaticamente em um conjunto de array.
        array_push(self::$routes,    $params[0]);
        // adiciona callbacks/controlador@metodo passados em um conjunto de array;
        array_push(self::$callbacks, $params[1]);
    }

    public static function invokeObject($callback, $matched = null, $message = null) {
        // cria um array a partir da /
        $last = explode('/', $callback);
        // pega o ultimo valor do array
        $last = end($last);

        // cria um array do controlador e metodo
        $segments   = explode('@', $last);

        // define o controlador
        $controller = $segments[0];
        // define o metodo
        $method     = $segments[1];

        // habilita o controlador
        $controller = "\\App\\Source\\Controllers\\" . $controller;
        $controller = new $controller($message);

        // verifica se o método existe no controlador
        if(!method_exists($controller, $method)) {
            // se não existir, msg de erro
            Response::error(404);
        }

        // executa o método do controlador
        call_user_func_array([$controller,$method], $matched ? $matched : []);
    }

    public static function autoDispatch() {
        // Carrega todos os filtros disponiveis
        if(Filter::$autoload) {
            Filter::getAll();
        }

        // define o controlador
        $controller = Request::getController() ? Request::getController() : DEFAULT_CONTROLLER;
        // define o metodo
        $method     = Request::getMethod() ? Request::getMethod() : DEFAULT_METHOD;
        // define os argumentos
        $args       = Request::getArgs();

        // habilita o controlador
        $controller = CONTROLLER_PREFIX . ucfirst($controller) . CONTROLLER_SUFIX;

        // verifica se o método existe no controlador
        if(method_exists($controller, $method)) {
            $controller = new $controller();
            // executa o método do controlador
            call_user_func_array([$controller,$method], $args ? $args : []);
            // rota foi encontrada.
            return true;

        } else {
            // nenhuma rota foi encontrada.
            return false;
        }
    }

    public static function dispatch() {
        // carrega todas as rotas criadas.
        self::getAll();

        // ṕega a requisição atual
        Request::uri();

        // pega as chaves das exp. regular
        $searches = array_keys(static::$patterns);
        // pega os valores das exp. regular
        $replaces = array_values(static::$patterns);

        // callback sem argumentos ou injeção de controlador e método
        // se o controlador passado existir
        if(in_array('/' . Request::getController(), self::$routes)) {
            // ṕega a sua posição
            (Integer) $route_pos = array_flip(self::$routes)['/' . Request::getController()];

            // se for uma posição válida
            if(isset($route_pos)) {
                // verifica se o método de requisição é válido
                if(Request::header('request-method') === self::$methods[$route_pos] || 'ANY' === self::$methods[$route_pos]) {
                    // a rota informada existe!
                    (Bool) $found_route = true;

                    // se a rota possuir callback
                    if(is_callable(self::$callbacks[$route_pos])) {
                        // executa a callback
                        call_user_func(self::$callbacks[$route_pos]);
                        return;
                    } else {
                        // se for injeção de controlador e metodo, executa o mesmo.
                        self::invokeObject(self::$callbacks[$route_pos]);
                        return;
                    }
                }
            }
        } else {
            // callback com passagem de argumentos
            // verifica todas as rotas
            foreach(self::$routes as $route_pos => $route) {
                // remove a '/' inicial
                $route = ltrim($route, '/');

                // verifica a callback tem passagem de argumentos
                if(strpos($route, ':') !== false) {
                    // prepara os argumentos para exp. regular
                    $route = str_replace($searches, $replaces, $route);
                }

                // verifica se a exp. regular é válida
                if(preg_match('#^'.$route.'$#', Request::getUri(), $matched)) {
                    // verifica se o método de requisição é válido
                    if(Request::header('request-method') === self::$methods[$route_pos] || 'ANY' === self::$methods[$route_pos]) {
                        // a rota informada existe!
                        (Bool) $found_route = true;

                        // remove o primeiro e deixa apenas a combinação
                        array_shift($matched);

                        // se a rota possuir callback
                        if(is_callable(self::$callbacks[$route_pos])) {
                            // executa a callback
                            call_user_func_array(self::$callbacks[$route_pos], $matched);
                            return;
                        } else {
                            // se não, faz a injeção do controlador e método
                            self::invokeObject(self::$callbacks[$route_pos], $matched);
                            return;
                        }
                    }
                }
            }

            // se habilitado, sempre executará o auto dispatch.
            if(self::$autoDispatch) {
                $found_route = self::autoDispatch();
            } else {
                $found_route = false;
            }

            // se a rota passada não for encontrada
            if(!$found_route) {
                // responde com o erro 404.
                Response::error(404);
            }
        }
    }

    public static function getAll() {
        foreach(glob(PROJECT_DIR . 'app/Routers/router.*.php') as $file) {
            if($file = getFilePath($file)) {
                require_once $file;
            }
        }
    }
}

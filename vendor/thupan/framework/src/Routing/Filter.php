<?php

namespace Routing;

class Filter {
    // array de filtros
    protected static $filter = [];

    // autoload padrao
    public static $autoload = true;

    // adicionar uma closure no array de filtros
    public static function add($name, $callback) {
        self::$filter[$name] = $callback;
    }

    // pega apenas uma closure dentro do array de filtros
    public static function get($name) {
        if(is_callable(self::$filter[$name])) {
            call_user_func(self::$filter[$name]);
            return;
        } else {
            return false;
        }
    }

    // importa todas as closure do array de filtros
    public static function getAll() {
        foreach(glob(PROJECT_DIR . 'app/Routers/Filters/filter.*.php') as $file) {
            if($file = getFilePath($file)) {
                require_once $file;
            }
        }

        foreach(self::$filter as $key => $closure) {
            if(is_callable($closure)) {
                call_user_func($closure);
            }
        }
    }
}

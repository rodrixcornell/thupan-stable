<?php

namespace Routing;

class Filter {
    protected static $filter = [];

    public static $autoload = true;

    public static function add($name, $callback) {
        self::$filter[$name] = $callback;
    }

    public static function get($name) {
        return self::$filter[$name];
    }

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

<?php

namespace Kernel;

class View {

    protected static $twig = null;

    public static $data = [];

    public static function init() {
        $loader = new \Twig_Loader_Filesystem(PROJECT_DIR . TEMPLATE_DIR);
        self::$twig = new \Twig_Environment($loader, array(
            'cache'         => PROJECT_DIR . CACHE_DIR,
            'auto_reload'   => TWIG_AUTO_RELOAD,
            'autoescape'    => TWIG_AUTO_ESCAPE,
            'debug'         => DEBUG,
        ));

        foreach(self::$data as $key => $value) {
            self::$twig->addGlobal($key, $value);
        }
    }

    public static function getInstance() {
        if(!self::$twig) {
            self::init();
        }
        return self::$twig;
    }

    public static function render($template, $data = []) {
        $template = str_replace('.','/', $template) . '.twig';
        echo self::getInstance()->render($template, $data);
    }

    public static function assign($key, $value) {
        self::$data[$key] = $value;
    }
}

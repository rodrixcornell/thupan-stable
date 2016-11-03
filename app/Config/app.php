<?php

return [
    'PROTOCOL'                  => 'http', //https
    'DEBUG'                     => true, //false
    'KEY'                       => '5a28b6f09fbf993ede7d4c2bf7066e95',
    'CRYPT_SECURE'              => 'high', //low
    'TIMEZONE'                  => 'America/Manaus',
    'LOCALE'                    => 'pt_BR', //en
    'DEFAULT_CONTROLLER'        => 'painel',
    'DEFAULT_METHOD'            => 'index',
    'CONTROLLER_PREFIX'         => "\\App\\Source\\Controllers\\",
    'CONTROLLER_SUFIX'          => 'Controller',
    'MODEL_PREFIX'              => "\\App\\Source\\Models\\",
    'MODEL_SUFIX'               => '',
    'TEMPLATE_DIR'              => 'app/Source/Views/',
    'CACHE_DIR'                 => 'app/Store/Cache/',
    'TWIG_AUTO_ESCAPE'          => true,
    'TWIG_AUTO_RELOAD'          => true,
    'TWIG_TAG_BLOCK'            => ['{%', '%}'],
    'TWIG_TAG_VARIABLE'         => ['{{', '}}'],
    'TWIG_TAG_COMMENT'          => ['{#', '#}'],
    'TWIG_TAG_INTERPOLATION'    => ['#{', '}'],
    'TWIG_PAGE_LANG'            => 'pt_BR',
    'TWIG_PAGE_CHARSET'         => 'utf8',
];

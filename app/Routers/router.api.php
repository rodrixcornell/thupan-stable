<?php

use \Routing\Router;

Router::get('/api/auth/(:any)/(:num)', function($username, $password) {

    echo 'usuario: ' . $username . ' senha:' . $password;

});

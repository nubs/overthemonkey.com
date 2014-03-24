<?php
use Aura\Di\Container;

return function(Container $app) {
    $templater = require __DIR__ . '/config/templater.php';
    $templater($app);

    $router = require __DIR__ . '/config/router.php';
    $router($app);
};

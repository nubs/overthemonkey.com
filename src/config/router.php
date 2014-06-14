<?php
use Aura\Di\Container;
use Aura\Router\RouterFactory;

return function(Container $app) {
    $app->set('router', function() {
        $router = (new RouterFactory())->newInstance();
        $router->add('home', '/');
        $router->add('resume', '/resume');
        $router->add('portfolio', '/portfolio');

        return $router;
    });
};

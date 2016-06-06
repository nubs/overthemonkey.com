<?php
use Aura\Di\Container;
use Aura\Router\RouterContainer;

return function(Container $app) {
    $app->set('router', function() {
        $router = new RouterContainer();
        $map = $router->getMap();
        $map->get('home', '/');
        $map->get('resume', '/resume');
        $map->get('portfolio', '/portfolio');
        $map->get('blog-list', '/blog');
        $map->get('blog-post', '/blog/{id}');

        return $router;
    });
};

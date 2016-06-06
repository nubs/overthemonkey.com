<?php
use Aura\Di\ContainerBuilder;
use Zend\Diactoros\ServerRequestFactory;

$appDir = dirname(__DIR__);
require_once "{$appDir}/vendor/autoload.php";

$containerBuilder = new ContainerBuilder();
$app = $containerBuilder->newInstance();

$config = require "{$appDir}/src/config.php";
$config($app);

$request = ServerRequestFactory::fromGlobals($_SERVER);
$route = $app->get('router')->getMatcher()->match($request);

if ($route === false) {
    http_response_code(404);
    echo 'Not Found';
    exit(1);
}

$attributes = ['page' => $route->handler, 'posts' => ['which-which-is-which']];
$handler = $route->handler;
if (isset($route->attributes['id'])) {
    $attributes['id'] = $route->attributes['id'];
    $handler = "blog/{$route->attributes['id']}";
}

echo $app->get('templater')->loadTemplate("{$handler}.twig")->render($attributes);

<?php
use Aura\Di\Container;
use Aura\Di\Factory;

$appDir = dirname(__DIR__);
require_once "{$appDir}/vendor/autoload.php";

$app = new Container(new Factory());

$config = require "{$appDir}/src/config.php";
$config($app);

$route = $app->get('router')->match(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $_SERVER);

if ($route === false) {
    http_response_code(404);
    echo 'Not Found';
    exit(1);
}

$params = ['page' => $route->params['action'], 'posts' => ['which-which-is-which']];
$action = $route->params['action'];
if (isset($route->params['id'])) {
    $params['id'] = $route->params['id'];
    $action = "blog/{$route->params['id']}";
}

echo $app->get('templater')->loadTemplate("{$action}.twig")->render($params);

<?php
use Aura\Di\Container;

return function(Container $app) {
    $app->params['Twig_Loader_Filesystem'] = ['paths' => [dirname(__DIR__) . '/templates']];
    $app->params['Twig_Environment'] = ['loader' => $app->lazyNew('Twig_Loader_Filesystem')];

    $app->set('templater', $app->lazyNew('Twig_Environment'));
};

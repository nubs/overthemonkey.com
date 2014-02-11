<?php
use Aura\Di\Container;

return function(Container $app) {
    $app->params['Aura\Dispatcher\Dispatcher'] = [
        'objects' => [
            'home' => function() use($app) {
                return $app->get('templater')->loadTemplate('home.twig')->render([]);
            },
        ],
        'object_param' => 'action',
    ];

    $app->set('dispatcher', $app->lazyNew('Aura\Dispatcher\Dispatcher'));
};

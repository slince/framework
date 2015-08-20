<?php
use Slince\Di\Container;
use Slince\Router\RequestContext;
use Slince\Router\RouterFactory;

return [
    'db' => [
        'class' => 'Cake\Database',
    ],
    'router' => function(Container $container) {
         $context = RequestContext::create()->fromRequest($container->get('app')->getRequest());
         return RouterFactory::create($context);
    },
    'cache' => [
        'class' => 'Slince\Cache\Cache',
        'arguments' => [
        ]
    ]
];
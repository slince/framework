<?php
use Slince\Di\Container;
use Slince\Router\RequestContext;
use Slince\Applicaion\WebApplication;
use Slince\Router\RouterFactory;

return [
    'db' => [
        'class' => 'Cake\Database',
    ],
    'router' => function(Container $container, WebApplication $app) {
         $context = RequestContext::create()->fromRequest($app->getRequest());
         return RouterFactory::create($context);
    },
    'cache' => [
        'class' => 'Slince\Cache\Cache',
        'arguments' => [
        ]
    ]
];
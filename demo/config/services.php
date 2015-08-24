<?php
use Slince\Di\Container;
use Slince\Router\RequestContext;
use Slince\Router\RouterFactory;

return [
    'router' => function(Container $container) {
         $context = RequestContext::create()->fromRequest($container->get('app')->getRequest());
         $router = RouterFactory::create($context);
         $routes = $router->getRoutes();
         $routeCreateCallback = include __DIR__ . '/routes.php';
         call_user_func($routeCreateCallback, $routes);
         return $router;
    },
    'view' => [
        'class' => 'Slince\\Router\\ServiceFactory',
        'arguments'
    ]
];
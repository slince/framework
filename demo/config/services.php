<?php
use Slince\Di\Container;
use Slince\Routing\RequestContext;
use Slince\Routing\RouterFactory;
use Slince\View\ServiceFactory;

return [
    'router' => function(Container $container) {
         $context = RequestContext::create()->fromRequest($container->get('app')->getRequest());
         $router = RouterFactory::create($context);
         $routes = $router->getRoutes();
         $routeCreateCallback = include __DIR__ . '/routes.php';
         call_user_func($routeCreateCallback, $routes);
         return $router;
    },
    'view' => function(Container $container) {
        return ServiceFactory::get('native', [
            'viewPath' => RESOURCE_PATH . 'views',
            'layoutPath' => RESOURCE_PATH . 'layouts',
            'elementPath' => RESOURCE_PATH .'elements',
        ]);
    },
];
<?php
use Slince\Di\Container;
use Slince\Router\RequestContext;
use Slince\Router\RouterFactory;
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
        $rootPath = $container->get('app')->getRootPath();
        return ServiceFactory::get('native', [
            'viewPath' => $rootPath . '/src/Resource/views',
            'layoutPath' => $rootPath . '/src/Resource/layouts',
            'elementPath' => $rootPath . '/src/Resource/elements',
        ]);
    },
];
<?php
use Slince\Application\Kernel;
use Slince\Routing\RouteCollection;
use Slince\Di\Container;

class AppKernel extends Kernel
{

    function getRootPath()
    {
        return __DIR__ . '/../';
    }

    function registerApplications()
    {
        $this->registerApplication('Web',  new Web\WebApplication());
    }

    function registerServices(Container $container)
    {
        $callback = include $this->getRootPath() . 'config/services.php';
        call_user_func($callback, $container);
    }

    function registerRoutes(RouteCollection $routes)
    {
        $callback = include $this->getRootPath() . 'config/routes.php';
        call_user_func($callback, $routes);
    }
}

<?php
namespace App;

use Slince\Application\Kernel;
use Slince\Routing\RouteCollection;
use Slince\Di\Container;
use Slince\Application\EventStore;
use Slince\Event\Dispatcher;
use Slince\Application\Subscriber\CakeSubscriber;
use Slince\Config\Config;

class AppKernel extends Kernel
{

    function getRootPath()
    {
        return __DIR__ . '/../';
    }

    function registerApplications()
    {
        $this->registerApplication(new \DefaultApplication\DefaultApplication());
    }

    function registerConfigs(Config $config)
    {
        $config->load($this->getRootPath() . '/config/app.php');
    }

    function registerServices(Container $container)
    {
        $callback = include $this->getRootPath() . 'config/services.php';
        call_user_func($callback, $container, $this);
    }

    function registerSubscribers(Dispatcher $dispatcher)
    {
        $dispatcher->addSubscriber(new CakeSubscriber());
    }
    
    function registerRoutes(RouteCollection $routes)
    {
        $callback = include $this->getRootPath() . 'config/routes.php';
        call_user_func($callback, $routes);
    }
}

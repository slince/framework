<?php
use Slince\Di\Container;
use Slince\Routing\RouterFactory;
use Slince\View\ServiceFactory;

return function (Container $container) {
    // config
    $container->alias('config', '\\Slince\Config\Config');
    // dispatcher
    $container->alias('dispatcher', '\\Slince\Event\Dispatcher');
    // kernel cache
    $container->alias('kernelCache', '\\Slince\Cache\ArrayCache');
    
    // router
    $container->share('router', function () {
        return RouterFactory::create();
    });
    
    $container->share('view', function(){
        return ServiceFactory::get('native');
    });
};
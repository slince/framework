<?php
use Slince\Di\Container;
use Slince\Routing\RouterFactory;
use Slince\View\ServiceFactory;
use Slince\Cache\FileCache;
use Slince\Application\Kernel;
use Slince\Di\Definition;

return function (Container $container, Kernel $kernel) {
    //核心组件
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
    $container->share('cache', function (){
        return new FileCache($path);
    });
    $container->setDefinition('cache', new Definition('\\Slince\\Cache\\FileCache', [
        $kernel->getRootPath() . 'tmp/cache'
    ]), true);
};
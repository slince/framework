<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

use Slince\Di\Container;
use Slince\Event\Dispatcher;

class KernelServiceFactory
{

    /**
     * 创建di容器
     * 
     * @return \Slince\Di\Container
     */
    static function createContainer()
    {
        return new Container();
    }

    /**
     * 创建事件调度器实例
     * 
     * @return \Slince\Event\Dispatcher
     */
    static function createDispatcher()
    {
        return new Dispatcher();
    }

    static function createServiceTranslator(Container $container)
    {
        return new ServiceTranslator($container);
    }
    
    static function createEventRegistry(ApplicationInterface $application)
    {
        return new EventRegistry($application);
    }
}
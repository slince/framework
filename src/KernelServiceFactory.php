<?php
namespace Slince\Applicaion;

use Slince\Config\Repository;
use Slince\Di\Container;
use Slince\Event\Dispatcher;
use Slince\Event\Event;

class KernelServiceFactory
{

    static function createContainer()
    {
        return new Container();
    }

    static function createDispatcher()
    {
        return new Dispatcher();
    }

    static function createServiceTranslator(Container $container)
    {
        return new ServiceTranslator($container);
    }
}
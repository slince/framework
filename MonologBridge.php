<?php
/**
 * slince monolog bridge
 * 
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\MonologBridge;

use Slince\Application\Bridge;
use Slince\Di\Container;
use Monolog\ErrorHandler;

class MonologBridge extends Bridge
{
    function initialize(Container $container)
    {
        ErrorHandler::register($container->get('log'));
    }
}
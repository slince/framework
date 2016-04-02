<?php
/**
 * slince whoops bridge
 * 
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\WhoopsBridge;

use Slince\Application\Bridge;
use Slince\Di\Container;

class WhoopsBridge extends Bridge
{
    function initialize(Container $container)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
}
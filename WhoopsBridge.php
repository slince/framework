<?php
/**
 * slince whoops bridge
 * 
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\WhoopsBridge;

class WhoopsBridge extends Bridge
{
    function initialize()
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
}
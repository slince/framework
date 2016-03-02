<?php
/**
 * slince application library
 * 
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application\Listener;

use Slince\Event\EventInterface;
use Slince\Event\ListenerInterface;
use Monolog\ErrorHandler;

class WhoopsListener implements ListenerInterface
{

    function handle(EventInterface $event)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
}
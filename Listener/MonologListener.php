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

class MonologListener implements ListenerInterface
{

    function handle(EventInterface $event)
    {
        $logger = $event->getSubject()
            ->getContainer()
            ->get('log');
        ErrorHandler::register($logger);
    }
}
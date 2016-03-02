<?php
namespace Slince\Application\ErrorHandler;

use Slince\Event\SubscriberInterface;
use Slince\Event\Event;
use Slince\Application\EventStore;
use Monolog\ErrorHandler;

abstract class ErrorHandler implements SubscriberInterface
{

    function getEvents()
    {
        return [
            EventStore::ERROR_OCCURRED => 'catchError',
            EventStore::EXCEPTION_OCCURRED => 'catchException'
        ];
    }

    function catchError(Event $event)
    {
        $arguments = $event->getArguments();

    }
}
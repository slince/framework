<?php
namespace Slince\Application\Subscriber;

use Slince\Event\SubscriberInterface;
use Slince\Event\Event;
use Slince\Application\EventStore;

class ErrorHandler implements SubscriberInterface
{

    function getEvents()
    {
        return [
            EventStore::ERROR_OCCURRED => 'onError',
            EventStore::EXCEPTION_OCCURRED => 'onException'
        ];
    }

    function onError(Event $event)
    {
        $event->stopPropagation();
        $kernel = $event->getSubject();
        $kernel->getContainer()->get('view')->load('500')->render();
    }
    
    function onException(Event $event)
    {
        $event->stopPropagation();
        $event->getSubject()->getApplication()->getController()->getViewManager()->load('404')->render();
    }
}
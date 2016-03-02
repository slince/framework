<?php
namespace Slince\Application\Subscriber;

use Slince\Event\SubscriberInterface;
use Slince\Event\Event;
use Slince\Application\EventStore;
use Slince\Application\Kernel;

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
        $viewManager = $this->getViewManager($event->getSubject());
        $viewManager->load('500')->render();
    }
    
    function onException(Event $event)
    {
        $event->stopPropagation();
        $viewManager = $this->getViewManager($event->getSubject());
        $viewManager->load('404')->render();
    }
    
    function getViewManager(Kernel $kernel)
    {
        $viewManager = $kernel->getContainer()->get('view');
        $viewManager->setViewPath($kernel->getApplication()->getViewPath() . 'error/');
        return $viewManager;
    }
}
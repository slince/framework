<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application\Bridge;

use Slince\Event\SubscriberInterface;
use Slince\Application\EventStore;

abstract class Bridge implements SubscriberInterface, BridgeInterface
{
    function getEvents()
    {
        return [
            EventStore::APP_INITED => 'init'
        ];
    }
}
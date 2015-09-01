<?php
namespace Slince\Application\Bridge;

use Slince\Event\Event;

interface BridgeInterface
{
    function init(Event $event);
}
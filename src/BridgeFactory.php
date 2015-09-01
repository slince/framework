<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

use Slince\Application\Bridge\CakeBridge;

class BridgeFactory
{
    static function createAllBridges()
    {
        return [
            new CakeBridge()
        ];
    }
}
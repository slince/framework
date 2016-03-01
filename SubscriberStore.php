<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

class SubscriberStore
{
    static function all()
    {
        return [
            'Slince\Application\Bridge\CakeBridge',
        ];
    }
}
<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application\Exception;

class MissActionException extends NotFoundException
{
    function __construct($controllerName, $actionName, $code = 404)
    {
        $message = sprintf('Action %s::%s() could not be found, or is not accessible.', $controllerName, $actionName);
        parent::__construct($message, $code);
    }
}
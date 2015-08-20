<?php
namespace Slince\Application;

class MissActionException extends \Exception
{
    function __construct($controllerName, $actionName, $code = 404)
    {
        $message = sprintf('Action %s::%s() could not be found, or is not accessible.', $controllerName, $actionName);
        parent::__construct($message, $code);
    }
}
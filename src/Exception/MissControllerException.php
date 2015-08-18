<?php
namespace Slince\Applicaion;

class MissControllerException extends \Exception
{
    function __construct($controllerName, $code = 404)
    {
        $message = sprintf('Controller "%s" could not be found, or is not accessible.', $controllerName);
        parent::__construct($message, $code);
    }
}
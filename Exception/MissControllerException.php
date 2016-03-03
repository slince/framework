<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application\Exception;

class MissControllerException extends NotFoundException
{
    function __construct($controllerName, $code = 404)
    {
        $message = sprintf('Controller "%s" could not be found, or is not accessible.', $controllerName);
        parent::__construct($message, $code);
    }
}
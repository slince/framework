<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Exception;

class FileNotExistsException extends ViewException
{

    function __construct($viewFile)
    {
        $message = sprintf('View or element  "%s" does not exist', $viewFile);
        parent::__construct($message);
    }
}
<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config;

class Config implements ConfigInterface, \ArrayAccess
{
    protected $_data;
    
    function __construct($filePath = null)
    {
    }
}
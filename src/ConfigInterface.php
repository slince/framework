<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config;

interface ConfigInterface
{
    function get($key, $default = null);
    
    function set($key, $value);
    
    function delete($key);
}
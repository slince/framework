<?php
/**
 * slince view component
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\View;

class View
{
    private $_blocks;
    private $_elements;
    
    function start($name)
    {
        $this->_elements[$name] = Factory::createBlock();
        ob_start();
    }
    function end()
    {
        
    }
}
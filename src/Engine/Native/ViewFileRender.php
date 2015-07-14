<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

class ViewFileRender
{
    protected $_vars = [];
    
    function set($name, $value = null)
    {
        if (is_array($name)) {
            $this->_vars = $name;
        }
        $this->_vars[$name] = $value;
    }
    
    function render(ViewFileInterface $viewFile)
    {
        
    }
}
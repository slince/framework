<?php
/**
 * slince view component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View;

class Theme
{

    protected $_path;

    function setPath($path)
    {
        $this->_path = $path;
    }

    function getPath()
    {
        return $this->_path;
    }
}
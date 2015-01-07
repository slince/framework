<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View;

use Slince\View\ViewFileInterface;

class AbstractViewFile implements ViewFileInterface
{
    protected $_viewFile;
    
    function __construct($viewFile)
    {
        $this->_viewFile = $viewFile;
    }
    function getViewFile()
    {
        return $this->_viewFile;
    }
}
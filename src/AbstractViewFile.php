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
    
    /**
     * (non-PHPdoc)
     * @see \Slince\View\ViewInterface::render()
     */
    function render()
    {
        return ViewRender::render($this);
    }
}
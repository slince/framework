<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View;

use Slince\View\ViewFileInterface;

class AbstractViewFile implements ViewFileInterface
{

    /**
     * 文件视图位置
     *
     * @var string
     */
    protected $_viewFile;

    function __construct($viewFile)
    {
        $this->_viewFile = $viewFile;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\View\ViewFileInterface::getViewFile()
     */
    function getViewFile()
    {
        return $this->_viewFile;
    }
}
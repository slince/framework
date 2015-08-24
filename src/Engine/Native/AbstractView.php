<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

abstract class AbstractView implements ViewInterface
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
     * @see \Slince\View\Engine\Native\ViewInterface::getViewFile()
     */
    function getViewFile()
    {
        return $this->_viewFile;
    }

    /**
     * (non-PHPdoc)
     * @see \Slince\View\Engine\Native\ViewInterface::setViewFile()
     */
    function setViewFile($viewFile)
    {
        $this->_viewFile = $viewFile;
    }
}
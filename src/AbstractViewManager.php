<?php
/**
 * slince view component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View;

abstract class AbstractViewManager implements ViewManagerInterface
{

    protected $_viewPath;

    /**
     * 布局文件位置
     *
     * @var string
     */
    protected $_layoutPath;

    function __construct($configs = [])
    {
        foreach ($configs as $key => $config) {
            $property = "_{$key}";
            if (property_exists($this, $property)) {
                $this->$property = $config;
            }
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\View\ViewManagerInterface::setViewPath()
     */
    function setViewPath($path)
    {
        $this->_viewPath = $path;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\View\ViewManagerInterface::getViewPath()
     */
    function getViewPath()
    {
        return $this->_viewPath;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\View\ViewManagerInterface::setLayoutPath()
     */
    function setLayoutPath($path)
    {
        $this->_layoutPath = $path;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\View\ViewManagerInterface::getLayoutPath()
     */
    function getLayoutPath()
    {
        return $this->_layoutPath;
    }
}
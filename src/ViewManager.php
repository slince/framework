<?php
/**
 * slince view component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View;

class ViewManager implements ViewManagerInterface
{

    protected $_viewPath;

    protected $_layoutPath;

    /**
     * 局部视图位置
     *
     * @var string
     */
    protected $_elementPath;

    /**
     * 布局文件位置
     *
     * @var string
     */
    protected $_layoutPath;

    function setViewPath($path)
    {
        $this->_viewPath = $path;
    }

    function getViewPath()
    {
        return $this->_viewPath;
    }

    function getLayoutPath()
    {
        return $this->_layoutPath;
    }

    function getElementPath()
    {
        $this->_elementPath;
    }

    /**
     * 设置局部视图文件位置
     *
     * @param string $path            
     */
    function setElementPath($path)
    {
        $this->_elementPath = $path;
    }

    /**
     * 设置局部视图文件位置
     *
     * @param string $path            
     */
    function setLayoutPath($path)
    {
        $this->_layoutPath = $path;
    }
}
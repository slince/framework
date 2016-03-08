<?php
/**
 * slince view library
 * 
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

use Slince\View\ViewManager as BaseViewManager;

class ViewManager extends BaseViewManager
{

    /**
     * 扩展名
     *
     * @var string
     */
    protected $_ext = 'php';

    /**
     * 局部视图位置
     *
     * @var string
     */
    protected $_elementPath;

    /**
     * 视图渲染器
     *
     * @return ViewRender
     */
    function getViewRender()
    {
        return ViewRender::newInstance($this);
    }

    /**
     * 设置局部视图位置
     *
     * @param string $path
     */
    function setElementPath($path)
    {
        $this->_elementPath = rtrim($path, '/') . DIRECTORY_SEPARATOR;
    }

    /**
     * 获取局部视图位置
     */
    function getElementPath()
    {
        $this->_elementPath;
    }

    /**
     * 获取局部视图位置
     *
     * @param string $name
     * @return string
     */
    function getElementFile($name)
    {
        return "{$this->_elementPath}{$name}.{$this->_ext}";
    }

    /**
     * 获取布局文件位置
     *
     * @param string $name
     * @return string
     */
    function getLayoutFile($name)
    {
        return "{$this->_layoutPath}{$name}.{$this->_ext}";
    }

    /**
     * 获取视图文件位置
     *
     * @param string $name
     * @return string
     */
    function getViewFile($name)
    {
        return "{$this->_viewPath}{$name}.{$this->_ext}";
    }

    /**
     * 加载一个模板
     *
     * @param string $name
     * @return \Slince\View\View
     */
    function load($name, $layout = null)
    {
        $viewFile = $this->getViewFile($name);
        $layoutFile = empty($layout) ? null : $this->getLayoutFile($layout);
        return ViewFactory::createView($viewFile, $layoutFile, $this);
    }
}
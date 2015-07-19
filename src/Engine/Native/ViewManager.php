<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

use Slince\View\ViewManager;

class ViewManager extends ViewManager
{

    protected $_ext = 'php';

    function getViewRender()
    {
        return ViewRender::newInstance();
    }

    /**
     * 获取局部视图位置
     *
     * @param string $name            
     * @return string
     */
    function getElementFile($name)
    {
        return "{$this->_elementPath}.{$name}.{$this->_ext}";
    }

    /**
     * 获取布局文件位置
     *
     * @param string $name            
     * @return string
     */
    function getLayoutFile($name)
    {
        return "{$this->_layoutPath}.{$name}.{$this->_ext}";
    }
}
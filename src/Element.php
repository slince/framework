<?php
/**
 * slince view library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\View;

class Element implements ViewInterface
{

    /**
     * 视图位置
     *
     * @var string
     */
    private $_path;

    /**
     * 局部视图归属
     *
     * @var View
     */
    private $_view;

    function __construct($path, View $view)
    {
        $this->_path = $path;
        $this->_view = $view;
    }

    function __call($name, $args)
    {
        return call_user_func_array(array(
            $this->_view,
            $name
        ), $args);
    }

    /**
     * 设置归属视图对象
     *
     * @param View $view            
     */
    function setView(View $view)
    {
        $this->_view = $view;
    }

    /**
     * 渲染视图
     *
     * @throws Exception\FileNotExistsException
     * @return string
     */
    function render()
    {
        return $this->_view->renderFile($this->_path);
    }

    /**
     * 魔术方法
     */
    function __toString()
    {
        return $this->render();
    }
}
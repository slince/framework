<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

interface ViewRenderInterface
{

    /**
     * 设置一个变量
     * 
     * @param string $name            
     * @param string $value            
     */
    function set($name, $value = null);

    /**
     * 渲染一个视图对象
     *
     * @param ViewInterface $viewFile            
     */
    function render(ViewInterface $viewFile);
}
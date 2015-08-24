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
    function setVariable($name, $value = null);

    /**
     * 批量设置对象
     * 
     * @param array $variables            
     */
    function setVariables(array $variables);

    /**
     * 批量添加一组变量
     * 
     * @param array $variables            
     */
    function addVariables(array $variables);

    /**
     * 渲染一个视图对象
     *
     * @param ViewInterface $viewFile            
     */
    function render(ViewInterface $viewFile);
}
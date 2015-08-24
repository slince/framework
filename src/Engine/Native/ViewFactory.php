<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

class ViewFactory
{

    /**
     * 创建视图块对象
     *
     * @param string $content            
     * @return Block
     */
    static function createBlock($content)
    {
        return new Block($content);
    }

    /**
     * 创建局部视图对象
     *
     * @param string $path            
     * @return Element
     */
    static function createElement($elementFile)
    {
        return new Element($elementFile);
    }
    
    /**
     * 创建layout
     * 
     * @param string $layoutFile
     * @return \Slince\View\Engine\Native\Layout
     */
    static function createLayout($layoutFile)
    {
        return new Layout($layoutFile);
    }
    
    /**
     * 创建视图对象
     * 
     * @param string $viewFile
     * @param ViewRender $viewRender
     * @param Layout $layout
     * @return \Slince\View\Engine\Native\View
     */
    static function createView($viewFile, ViewRender $viewRender, Layout $layout = null)
    {
        return new View($viewFile, $viewRender, $layout);
    }
}
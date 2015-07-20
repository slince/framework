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
    
    static function createLayout($layoutFile)
    {
        return new Layout($layoutFile);
    }
    
    static function createView($viewFile, ViewRender $viewRender, Layout $layout = null)
    {
        return new View($viewFile, $viewRender, $layout);
    }
}
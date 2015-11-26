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
    static function createBlock($content = '')
    {
        return new Block($content);
    }

    /**
     * 创建视图对象
     * 
     * @param string $viewFile
     * @param ViewManager $viewManager
     * @param Layout $layout
     * @return \Slince\View\Engine\Native\View
     */
    static function createView($viewFile, $layoutFile, ViewManager $viewManager)
    {
        return new View($viewFile, $layoutFile, $viewManager);
    }
}
<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View;

class ViewRender
{
    static $vars = [];
    
    /**
     * 渲染视图文件
     */
    static function render(ViewFileInterface $view)
    {
        if (! file_exists($path)) {
            throw new Exception\FileNotExistsException($path);
        }
        ob_start();
        extract(self::vars);
        include $view->getViewFile();
        return ob_get_clean();
    }
}
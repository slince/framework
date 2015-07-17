<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

use Slince\View\Exception\ViewFileNotExistsException;

class ViewRender implements ViewRenderInterface
{
    protected static $_instance;
    
    static function newInstance()
    {
        if (! self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    protected $_vars = [];
    
    function set($name, $value = null)
    {
        if (is_array($name)) {
            $this->_vars = $name;
        }
        $this->_vars[$name] = $value;
    }
    
    function render(ViewInterface $viewFile)
    {
        if (! is_file($viewFile->getViewFile())) {
            throw new ViewFileNotExistsException($viewFile->getViewFile());
        }
        extract($this->_vars);
        ob_start();
        include $viewFile->getViewFile();
        $content = ob_get_clean();
        return $content;
    }
}
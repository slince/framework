<?php
/**
 * slince view library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\View;

class Element
{

    private $_name;

    private $_path;

    private $_content;

    function __construct($path)
    {
        $this->_path = $path;
    }

    function render()
    {
        if (empty($this->_content)) {
            if (! file_exists($this->_path)) {
                throw new Exception\FileNotExistsException($this->_path);
            }
            ob_start();
            include $this->_path;
            $this->_content = ob_get_clean();
        }
        return $this->_content;
    }

    function __toString()
    {
        return $this->render();
    }
}
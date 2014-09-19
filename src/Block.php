<?php
/**
 * slince view library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\View;

class Block
{

    private $_id;

    private $_content;

    function __construct($content = '')
    {
        $this->_content = $content;
    }

    function setContent($content)
    {
        $this->_content = $content;
    }

    function render()
    {
        return $this->_content;
    }

    function __toString()
    {
        return $this->render();
    }
}
<?php
/**
 * slince view library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\View;

class Block implements ViewInterface
{

    /**
     * 块内容
     *
     * @var string
     */
    private $_content;

    function __construct($content = '')
    {
        $this->_content = $content;
    }

    /**
     * 设置内容
     * 
     * @param string $content            
     */
    function setContent($content)
    {
        $this->_content = $content;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see ViewInterface::render()
     */
    function render()
    {
        return $this->_content;
    }

    /**
     * 魔术方法
     */
    function __toString()
    {
        return $this->render();
    }
}
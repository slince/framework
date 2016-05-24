<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

class Block implements BlockInterface
{

    /**
     * 块内容
     *
     * @var string
     */
    protected $_content;

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
        return $this;
    }

    function getContent()
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
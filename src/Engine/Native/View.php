<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

use Slince\View\Exception\ViewException;
use Slince\View\Exception\ViewFileNotExistsException;

class View extends AbstractView
{

    /**
     * 使用的布局
     *
     * @var string
     */
    private $_layout;

    private $_content;

    /**
     *
     * @var ViewRenderInterface
     */
    protected $_viewRender;

    protected $_viewManager;

    function __construct($viewFile, ViewRenderInterface $viewRender, Layout $layout = null)
    {
        parent::__construct($viewFile);
        $this->_viewRender = $viewRender;
    }

    function setViewRender(ViewRenderInterface $viewRender)
    {
        $this->_viewRender = $viewRender;
    }

    function getViewRender()
    {
        return $this->_viewRender;
    }

    function set($name, $value = null)
    {
        $this->_viewRender->set($name, $value);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\View\Engine\Native\ViewInterface::render()
     */
    function render($vars = [], $useLayout = true)
    {
        $this->set($vars);
        if (! isset($this->_blocks['content'])) {
            $this->_blocks['content'] = $this->_viewRender->render($this);
        }
        if ($useLayout && ! is_null($this->_layout)) {
            return $this->_viewRender->render($this->_layout);
        }
        return $this->_blocks['content'];
    }
}
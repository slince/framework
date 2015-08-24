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
    protected $_layout;


    /**
     * 视图渲染器
     * 
     * @var ViewRenderInterface
     */
    protected $_viewRender;

    /**
     * 视图管理者
     * 
     * @var ViewManager
     */
    protected $_viewManager;

    function __construct($viewFile, ViewRenderInterface $viewRender, Layout $layout = null)
    {
        parent::__construct($viewFile);
        $this->_viewRender = $viewRender;
    }

    /**
     * 设置视图渲染器
     * 
     * @param ViewRenderInterface $viewRender
     */
    function setViewRender(ViewRenderInterface $viewRender)
    {
        $this->_viewRender = $viewRender;
    }

    /**
     * 获取视图渲染器
     * 
     * @return \Slince\View\Engine\Native\ViewRenderInterface
     */
    function getViewRender()
    {
        return $this->_viewRender;
    }

    /**
     * 设置一个变量或者一组变量
     * 
     * @param string|array $name
     * @param string $value
     */
    function set($name, $value = null)
    {
        if (is_array($name)) {
            $this->_viewRender->setVariables($name);
        } else {
            $this->_viewRender->setVariable($name, $value);
        }
    }
    
    /**
     * 添加一组变量
     * 
     * @param array $variables
     */
    function add(array $variables)
    {
        $this->_viewRender->addVariables($variables);
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
<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

use Slince\View\Exception\ViewException;
use Slince\View\Exception\ViewFileNotExistsException;
use Slince\View\Engine\Native\ViewFactory;

class View extends AbstractView
{

    /**
     * 视图块
     *
     * @var array
     */
    private $_blocks = [];

    /**
     * 局部视图
     *
     * @var array
     */
    private $_elements;

    /**
     * 使用的布局
     *
     * @var string
     */
    private $_layout;

    private $_content;
    
    /**
     * @var ViewRenderInterface
     */
    protected $_viewRender;
    
    protected $_viewManager;

    function __construct(ViewManager $viewManager, $viewFile, $layoutFile = null)
    {
        parent::__construct($viewFile);
        $this->_viewRender = $viewManager->getViewRender();
        $this->_viewManager = $viewManager;
        if (! is_null($layoutFile)) {
            $this->_layout = ViewFactory::createLayout(
                $viewManager->getLayoutFile($layoutFile)
            );
        }
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
     * 捕捉一个视图块
     *
     * @param string $name 
     */
    function start($name)
    {
        $this->_block[$name] = ViewFactory::createBlock();
        ob_start();
    }

    /**
     * 结束上一个视图块的捕捉
     */
    function stop()
    {
        if (($block = end($this->_blocks)) !== false) {
            $block->setContent(ob_get_clean());
        }
    }

    /**
     * 是否存在某个block
     *
     * @param string $name            
     */
    function hasBlock($name)
    {
        return isset($this->_blocks[$name]);
    }

    /**
     * 获取块的内容，块不存在会抛出异常
     *
     * @param string $name            
     * @throws Exception\ViewException
     */
    function fetch($name)
    {
        if (! $this->hasBlock($name)) {
            throw new ViewException(sprintf('Block "%s" does not exists', $name));
        }
        return $this->_blocks[$name]->getContent();
    }

    /**
     * 获取块的内容
     *
     * @param string $name            
     * @return string|null
     */
    function fetchOrFail($name)
    {
        try {
            return $this->fetch($name);
        } catch (ViewException $e) {
            return null;
        }
    }

    /**
     * 获取一个局部视图的内容
     *
     * @param string $name            
     */
    function element($name)
    {
        $this->_elements[] = $name;
        $element = ViewFactory::createElement($this->_viewManager->getElementFile($name));
        return $this->_viewRender->render($element);
    }
    
    /**
     * (non-PHPdoc)
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
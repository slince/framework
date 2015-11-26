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

    protected $_layout;
    
    /**
     * 视图管理者
     * 
     * @var ViewManager
     */
    protected $_viewManager;
    

    function __construct($viewFile, $layout, ViewManager $viewManager)
    {
        parent::__construct($viewFile);
        $this->_layout = $layout;
        $this->_viewManager = $viewManager;
    }

    /**
     * 设置视图渲染器
     * 
     * @param ViewManager $viewManager
     */
    function setViewManager(ViewManager $viewManager)
    {
        $this->_viewManager = $viewManager;
    }

    /**
     * 获取视图渲染器
     * 
     * @return \Slince\View\Engine\Native\ViewManager
     */
    function getViewManager()
    {
        return $this->_viewManager;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\View\Engine\Native\ViewInterface::render()
     */
    function render($variables = [], $useLayout = true)
    {
        $viewRender = $this->_viewManager->getViewRender();
        $viewRender->set($variables);
        if (! $viewRender->hasBlock('content')) {
            $block = ViewFactory::createBlock()->setContent($viewRender->render($this));
            $viewRender->addBlock('content', $block);
            return $block->getContent();
        }
        if ($useLayout && ! is_null($this->_layout)) {
            return $viewRender->renderFile($this->_viewManager->getLayoutFile($this->_layout));
        }
    }
}
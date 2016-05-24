<?php
/**
 * slince view library
 *
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

use Slince\View\Exception\ViewException;
use Slince\View\Exception\ViewFileNotExistsException;

class View extends AbstractView
{

    protected $_layoutFile;

    /**
     * 视图管理者
     *
     * @var ViewManager
     */
    protected $_viewManager;

    function __construct($viewFile, $layoutFile, ViewManager $viewManager)
    {
        parent::__construct($viewFile);
        $this->_layoutFile = $layoutFile;
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
        $content = '';
        if (! $viewRender->hasBlock('content')) {
            $content = $viewRender->render($this);
            $block = ViewFactory::createBlock($content);
            $viewRender->addBlock('content', $block);
        }
        if ($useLayout && ! is_null($this->_layoutFile)) {
            $content = $viewRender->renderFile($this->_layoutFile);
        }
        $viewRender->reset();
        return $content;
    }
}
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

    /**
     * 视图块
     *
     * @var array
     */
    protected $_blocks = [];

    /**
     * 局部视图
     *
     * @var array
     */
    protected $_elements;

    protected $_vars = [];

    static function newInstance()
    {
        if (! self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function set($name, $value = null)
    {
        if (is_array($name)) {
            $this->_vars = $name;
        } else {
            $this->_vars[$name] = $value;
        }
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
    function read($name)
    {
        $this->_elements[] = $name;
        echo $this->_viewManager->getElementFile($name);exit;
        $element = ViewFactory::createElement($this->_viewManager->getElementFile($name));
        return $this->render($element);
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
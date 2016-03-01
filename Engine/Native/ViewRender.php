<?php
/**
 * slince view library
 *
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

use Slince\View\Exception\ViewFileNotExistsException;

class ViewRender implements ViewRenderInterface
{

    /**
     * 当前视图渲染器实例
     *
     * @var ViewRender
     */
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

    /**
     * 设置的变量
     *
     * @var array
     */
    protected $_variables = [];

    /**
     * View manager
     *
     * @var ViewManager
     */
    protected $_viewManager;

    /**
     * 实例化当前对象，单例
     *
     * @return \Slince\View\Engine\Native\ViewRender
     */
    static function newInstance(ViewManager $viewManager)
    {
        if (! self::$_instance instanceof self) {
            self::$_instance = new self($viewManager);
        }
        return self::$_instance;
    }

    function __construct(ViewManager $viewManager)
    {
        $this->_viewManager = $viewManager;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\View\Engine\Native\ViewRenderInterface::setVariable()
     */
    function set($name, $value = null)
    {
        if (is_array($name)) {
            $this->_variables = array_merge($this->_variables, $name);
        } else {
            $this->_variables[$name] = $value;
        }
    }

    /**
     * 捕捉一个视图块
     *
     * @param string $name
     */
    function start($name)
    {
        $this->_blocks[$name] = ViewFactory::createBlock();
        ob_start();
    }

    /**
     * 结束上一个视图块的捕捉
     */
    function end()
    {
        if (($block = end($this->_blocks)) !== false) {
            $block->setContent(ob_get_clean());
        }
    }

    /**
     * 添加一个block
     *
     * @param string $name
     * @param Block $block
     */
    function addBlock($name, Block $block)
    {
        $this->_blocks[$name] = $block;
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
        $this->_elements[$name] = $this->renderFile($this->_viewManager->getElementFile($name));
        return $this->_elements[$name];
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\View\Engine\Native\ViewRenderInterface::render()
     */
    function render(ViewInterface $view)
    {
        return $this->renderFile($view->getViewFile());
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\View\Engine\Native\ViewRenderInterface::renderFile()
     */
    function renderFile($file)
    {
        if (! is_file($file)) {
            throw new ViewFileNotExistsException($file);
        }
        extract($this->_variables);
        ob_start();
        include $file;
        $content = ob_get_clean();
        return $content;
    }

    /**
     * 重置render，避免对下次解析view造成影响
     *
     * @return void
     */
    function reset()
    {
        $this->_blocks = [];
        $this->_elements = [];
        $this->_variables = [];
    }
}
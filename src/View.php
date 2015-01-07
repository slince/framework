<?php
/**
 * slince view component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View;

use Slince\View\Exception\ViewException;
use Slince\View\Exception\ViewFileNotExistsException;

class View extends AbstractViewFile implements ViewInterface
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
     * 局部视图位置
     *
     * @var string
     */
    private $_elementPath;

    /**
     * 使用的布局
     * 
     * @var string
     */
    private $_layout;
    
    private $_ext = 'php';
    
    private $_vars = [];
    
    private $_content;

    function __construct($viewFile, $layout = null)
    {
        $this->_layout = $layout;
        parent::__construct($viewFile);
    }
    /**
     * 设置局部视图文件位置
     *
     * @param string $path            
     */
    function setElementPath($path)
    {
        $this->_elementPath = $path;
    }

    /**
     * 批量设置变量
     *
     * @param array $vars            
     */
    function setVars($vars)
    {
        $this->_vars = array_merge($this->_vars, $vars);
    }

    /**
     * 设置变量
     *
     * @param string $name            
     * @param mixed $var            
     */
    function setVar($name, $var)
    {
        $this->_vars[$name] = $var;
    }
    
    /**
     * 捕捉一个视图块
     *
     * @param string $name            
     */
    function capture($name)
    {
        $this->_block[$name] = Factory::createBlock();
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
        return $this->_blocks[$name]->render();
    }

    /**
     * 获取块的内容
     *
     * @param string $name            
     * @return string|false
     */
    function fetchOrFail($name)
    {
        try {
            return $this->fetch($name);
        } catch (ViewException $e) {
            return false;
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
        $element = Factory::createElement($this->_getElementFile($name));
        return $this->renderViewFile($element->getViewFile());
    }

    /**
     * (non-PHPdoc)
     * @see \Slince\View\ViewInterface::render()
     */
    function render()
    {
        if (! isset($this->_blocks['content'])) {
            $this->_blocks['content'] = $this->renderWithoutLayout();
        }
        if (! is_null($this->_layout)) {
            return $this->renderViewFile($this->_layout);
        }
        return $this->_blocks['content'];
    }
    
    /**
     * 不带布局渲染页面
     * @return string
     */
    function renderWithoutLayout()
    {
        return $this->renderViewFile($this->getViewFile());
    }

    /**
     * 渲染视图文件
     * 
     * @throws Exception\FileNotExistsException
     * @return string
     */
    function renderViewFile($viewFile)
    {
        if (! file_exists($viewFile)) {
            throw new ViewFileNotExistsException($viewFile);
        }
        ob_start();
        extract($this->_vars);
        include $viewFile;
        return ob_get_clean();
    }
    
    /**
     * 获取局部视图位置
     *
     * @param string $name
     * @return string
     */
    private function _getElementFile($name)
    {
        return "{$this->_elementPath}.{$name}.{$this->_ext}";
    }
}
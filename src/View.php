<?php
/**
 * slince view component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View;

class View implements ViewFileInterface
{

    /**
     * 视图位置
     *
     * @var string
     */
    private $_path;

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

    function __construct($path, $layout = null)
    {
        $this->_path = $path;
        $this->_layout = $layout;
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
        ViewRender::$vars = array_merge(ViewRender::$vars, $vars);
    }

    /**
     * 设置变量
     *
     * @param string $name            
     * @param mixed $var            
     */
    function setVar($name, $var)
    {
        ViewRender::$vars[$name] = $var;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Slince\View\ViewFileInterface::getViewFile()
     */
    function getViewFile()
    {
        return $this->_path;
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
        if (! isset($this->_blocks[$name])) {
            throw new Exception\ViewException(sprintf('Block "%s" does not exists', $name));
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
        if (isset($this->_blocks[$name])) {
            return $this->_blocks[$name]->render();
        }
        return false;
    }

    /**
     * 获取一个局部视图的内容
     * 
     * @param string $name            
     */
    function element($name)
    {
        $this->_elements[] = $name;
        return Factory::createElement($this->_getElementFile($name))->render();
    }

    /**
     * (non-PHPdoc)
     * @see \Slince\View\ViewInterface::render()
     */
    function render()
    {
        if (! isset($this->_blocks['content'])) {
            $content = $this->renderWithoutLayout();
        }
        if (! is_null($this->_layout)) {
            if (! file_exists($this->_layout)) {
                throw new Exception\FileNotExistsException($this->_layout);
            }
            return $this->renderFile($this->_layout);
        }
        return $content;
    }

    /**
     * 不使用模板布局渲染
     */
    function renderWithoutLayout()
    {
        $this->_blocks['content'] = Factory::createBlock($this->renderFile($this->_path));
        return $this->_blocks['content']->render();
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
<?php
/**
 * slince view component
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\View;

class View
{
    private $_blocks;
    private $_elements;
    /**
     * 捕捉一个视图块
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
     * 获取块的内容，块不存在会报错
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
     * @param unknown $name
     * @return boolean
     */
    function fetchWithoutException($name)
    {
        if (isset($this->_blocks[$name])) {
            return $this->_blocks[$name]->render();
        }
        return false;
    }
    
    function element($name)
    {
        
    }
    private function _getElementFile()
    {
        
    } 
}
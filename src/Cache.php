<?php
/**
 * slince cache component
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Cache;

class Cache
{

    protected $_handler;

    function __construct(HandlerInterface $handler)
    {
        $this->setHandler($handler);
    }

    /**
     * 设置处理器
     *
     * @param HandlerInterface $handler            
     */
    function setHandler(HandlerInterface $handler)
    {
        $this->_handler = $handler;
    }

    /**
     * 获取处理器
     *
     * @return HandlerInterface
     */
    function getHandler()
    {
        return $this->_handler;
    }

    /**
     * 设置一个值
     *
     * @param string $key            
     * @param mixed $value            
     * @param int $duration            
     */
    function set($key, $value, $duration = 3600)
    {
        $this->_handler->set($key, $value, $duration);
    }

    /**
     * 如果不存在则设置一个新值
     *
     * @param string $key            
     * @param mixed $value            
     * @param int $duration            
     */
    function setIfNotExists($key, $value, $duration = 3600)
    {
        if (! $this->exists($key)) {
            $this->set($key, $value, $duration);
        }
    }

    /**
     * 判断一个值是否存在
     *
     * @param string $key            
     */
    function exists($key)
    {
        return $this->_handler->exists($key);
    }

    /**
     * 获取一个值
     *
     * @param string $key            
     */
    function get($key, $defaultValue = null)
    {
        $value = $this->_handler->get($key);
        return is_null($value) ? $defaultValue : $value;
    }

    /**
     * 删除一个值
     *
     * @param string $key            
     */
    function delete($key)
    {
        return $this->_handler->delete($key);
    }

    /**
     * 清空储存区
     */
    function flush()
    {
        $this->_handler->flush();
    }
}
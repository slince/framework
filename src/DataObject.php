<?php
/**
 * slince config component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config;

class DataObject implements \ArrayAccess, \Countable
{
    /**
     * 配置的值
     *
     * @var array
     */
    private $_data = [];

    function __construct($data)
    {
        $this->_data = $data;
    }
    /**
     * 创建该实例
     */
    static function create()
    {
        return self();
    }
    /**
     * 输出当前对象中保存的配置值
     *
     * @return array
     */
    function toArray()
    {
        return $this->_data;
    }

    /**
     * 设置新的配置值，已存在的键值将会被覆盖
     *
     * @param int|string $key            
     * @param mixed $value            
     * @return void
     */
    function set($key, $value)
    {
        $this->_data[$key] = $value;
    }

    /**
     * 获取某个键值对应的参数
     *
     * @param int|string $key            
     * @param mixed $defaultValue            
     * @return mixed
     */
    function get($key, $defaultValue = null)
    {
        return $this->exists($key) ? $this->_data[$key] : $defaultValue;
    }
    
    /**
     * 批量合并
     * @param array $data
     */
    function merge(array $data)
    {
        $this->_data = array_merge($this->_data, $data);
    }

    /**
     * 判断是否存在某个键值
     *
     * @param int|string $key            
     */
    function exists($key)
    {
        return isset($this->_data[$key]);
    }

    /**
     * 移除已存在的键值
     *
     * @param mixed $key            
     */
    function delete($key)
    {
        unset($this->_data[$key]);
    }

    /**
     * 清除所有的数据
     *
     * @return void
     */
    function clear()
    {
        $this->_data = [];
    }

    /**
     * 实现接口方法
     *
     * @param mixed $offset            
     */
    function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * 实现接口方法
     *
     * @param mixed $offset            
     * @param mixed $value            
     */
    function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * 实现接口方法
     *
     * @param mixed $offset            
     */
    function offsetUnset($offset)
    {
        $this->delete($offset);
    }

    /**
     * 实现接口方法
     *
     * @param mixed $offset            
     */
    function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    /**
     * 实现接口方法
     */
    function count()
    {
        return count($this->_data);
    }
}
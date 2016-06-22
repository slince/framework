<?php
/**
 * slince config component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config;

class DataObject implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * 配置的值
     *
     * @var array
     */
    protected $data = [];

    /**
     * 输出当前对象中保存的配置值
     *
     * @return array
     */
    function toArray()
    {
        return $this->data;
    }

    /**
     * 替换当前数据
     * 
     * @param array $data            
     */
    function replace(array $data)
    {
        $this->data = $data;
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
        $this->data[$key] = $value;
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
        return $this->exists($key) ? $this->data[$key] : $defaultValue;
    }

    /**
     * 批量合并
     * 
     * @param array $data            
     */
    function merge(array $data)
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * 判断是否存在某个键值
     * @param int|string $key
     * @return boolean
     */
    function exists($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * 移除已存在的键值
     * @param mixed $key
     */
    function delete($key)
    {
        unset($this->data[$key]);
    }

    /**
     * 清除所有的数据
     * @return void
     */
    function flush()
    {
        $this->data = [];
    }


    /**
     * 继承方法
     * @param mixed $offset
     * @return mixed
     */
    function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * 继承方法
     * @param mixed $offset
     * @param mixed $value
     */
    function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * 继承方法
     * @param mixed $offset
     */
    function offsetUnset($offset)
    {
        $this->delete($offset);
    }

    /**
     * 继承方法
     * @param mixed $offset
     * @return bool
     */
    function offsetExists($offset)
    {
        return $this->exists($offset);
    }


    /**
     * 继承方法
     * @return mixed
     */
    function count()
    {
        return count($this->data);
    }

    /**
     * 继承方法
     * @return \ArrayIterator
     */
    function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
}
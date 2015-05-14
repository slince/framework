<?php
/**
 * slince config component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config;

class Repository implements \ArrayAccess, \Countable
{
    /**
     * 当前实例
     * 
     * @var Repository
     */
    private $_instance;
    /**
     * 配置的值
     *
     * @var array
     */
    private $_keyMap = [];

    function __construct(ParserInterface $parser = null)
    {
        if (! is_null($parser)) {
            $this->_keyMap = $parser->parse();
        }
    }
    
    /**
     * 单例模式
     */
    function newInstance()
    {
        if (! $this->_instance instanceof self) {
            $this->_instance = new self();
        }
        return $this->_instance;
    }

    /**
     * 添加新的配置接口或数据
     *
     * @param ParserInterface|array $data            
     * @param string $key
     * @return void
     */
    function merge($data, $key = null)
    {
        if ($data instanceof ParserInterface) {
            $data =  $data->parse();
        }
        if (! is_null($key)) {
            $data = [$key => $data];
        }
        $this->_keyMap = array_merge($this->_keyMap, $data);
    }

    /**
     * 将当前对象中保存的值导出
     *
     * @param ParserInterface $parser            
     */
    function dump(ParserInterface $parser)
    {
        $parser->dump($this->_keyMap);
    }

    /**
     * 输出当前对象中保存的配置值
     *
     * @return array
     */
    function toArray()
    {
        return $this->_keyMap;
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
        $this->_keyMap[$key] = $value;
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
        return $this->exists($key) ? $this->_keyMap[$key] : $defaultValue;
    }

    /**
     * 判断是否存在某个键值
     *
     * @param int|string $key            
     */
    function exists($key)
    {
        return isset($this->_keyMap[$key]);
    }

    /**
     * 移除已存在的键值
     *
     * @param mixed $key            
     */
    function delete($key)
    {
        unset($this->_keyMap[$key]);
    }

    /**
     * 清除所有的数据
     *
     * @return void
     */
    function clear()
    {
        $this->_keyMap = [];
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
        return count($this->_keyMap);
    }
}
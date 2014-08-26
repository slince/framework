<?php
/**
 * slince config component
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Config;

class Repository implements \ArrayAccess
{

    /**
     * 配置的值
     *
     * @var array
     */
    private $_keyMap = [];

    function __construct(ParserInterface $parser = null)
    {
        if (! is_null($parser)) {
            $this->_keyMap = $this->_parser->read();
        }
    }

    /**
     * 充值当前配置对象，已存在的配置都会被清空
     *
     * @param ParserInterface $parser            
     * @return void
     */
    function renew(ParserInterface $parser)
    {
        $this->_keyMap = $parser->read();
    }

    /**
     * 合并新的配置到当前对象
     *
     * @param ParserInterface $parser            
     * @return void
     */
    function merge(ParserInterface $parser)
    {
        $this->_keyMap = array_merge($this->_keyMap, $parser->read());
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
    
}
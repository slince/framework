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
     * @var DataObject
     */
    private $_dataObject;

    function __construct()
    {
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
     * @param FileInterface $file
     */
    function dump(FileInterface $file)
    {
        $parser->dump($this->_keyMap);
    }
}
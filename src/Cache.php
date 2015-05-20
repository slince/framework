<?php
/**
 * slince cache component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

class Cache
{

    /**
     * 存储接口
     *
     * @var StorageInterface
     */
    protected $_storage;

    /**
     * 默认的缓存时间
     *
     * @var int
     */
    protected $_duration = 3600;

    function __construct(StorageInterface $storage)
    {
        $this->setStorage($storage);
    }

    /**
     * 设置处理器
     *
     * @param StorageInterface $storage            
     */
    function setStorage(StorageInterface $storage)
    {
        $this->_storage = $storage;
    }

    /**
     * 获取处理器
     *
     * @return StorageInterface
     */
    function getStorage()
    {
        return $this->_storage;
    }

    /**
     * 设置默认的缓存时间
     *
     * @param int $duration            
     */
    function setDuration($duration)
    {
        $this->_duration = $duration;
    }

    /**
     * 获取默认的缓存时间
     *
     * @return int
     */
    function getDuration()
    {
        return $this->_duration;
    }

    /**
     * 设置一个值
     *
     * @param string $key            
     * @param mixed $value            
     * @param int $duration            
     * @return boolean
     */
    function set($key, $value, $duration = null)
    {
        if (is_null($duration)) {
            $duration = $this->_duration;
        }
        return $this->_storage->set($key, $value, $duration);
    }

    /**
     * 如果不存在则设置一个新值
     *
     * @param string $key            
     * @param mixed $value            
     * @param int $duration            
     * @return boolean
     */
    function add($key, $value, $duration = null)
    {
        if (is_null($duration)) {
            $duration = $this->_duration;
        }
        return $this->_storage->add($key, $value, $duration);
    }

    /**
     * 判断一个值是否存在
     *
     * @param string $key            
     * @return boolean
     */
    function exists($key)
    {
        return $this->_storage->exists($key);
    }

    /**
     * 获取一个值
     *
     * @param unknown $key            
     * @param string $defaultValue            
     * @return mixed
     */
    function get($key, $defaultValue = null)
    {
        $value = $this->_storage->get($key);
        return ($value === false) ? $defaultValue : $value;
    }

    /**
     * 删除一个值
     *
     * @param string $key            
     * @return boolean
     */
    function delete($key)
    {
        return $this->_storage->delete($key);
    }

    /**
     * 清空储存区
     */
    function flush()
    {
        $this->_storage->flush();
    }
}
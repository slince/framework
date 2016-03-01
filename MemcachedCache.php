<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

class MemcachedCache extends AbstractCache
{

    /**
     * memcache实例
     *
     * @var \Memcache
     */
    private $_memcached;

    function __construct(\Memcached $memcached)
    {
        $this->_memcached = $memcached;
    }

    /**
     * 设置memcached
     *
     * @param \Memcached $memcached            
     */
    function setMemcached(\Memcached $memcached)
    {
        $this->_memcache = $memcache;
    }

    /**
     * 获取memcache
     *
     * @return Memcache
     */
    function getMemcached()
    {
        return $this->_memcached;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::_doSet()
     */
    protected function _doSet($key, $value, $duration)
    {
        return $this->_memcached->set($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::_doAdd()
     */
    protected function _doAdd($key, $value, $duration)
    {
        return $this->_memcached->add($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::_doGet()
     */
    protected function _doGet($key)
    {
        $val = $this->_memcached->get($key);
        if (is_null($val)) {
            $val = false;
        }
        return $val;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::_doExists()
     */
    protected function _doExists($key)
    {
        return $this->get($key) !== false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::_doDelete()
     */
    protected function _doDelete($key)
    {
        return $this->_memcached->delete($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::_doFlush()
     */
    protected function _doFlush()
    {
        return $this->_memcached->flush();
    }
}
<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

class memcacheCache extends AbstractCache
{

    /**
     * memcache实例
     *
     * @var \Memcache
     */
    private $_memcache;

    function __construct(\Memcache $memcache)
    {
        $this->_memcache = $memcache;
    }

    /**
     * 设置memcache
     *
     * @param \Memcache $memcache            
     */
    function setMemcache(\Memcache $memcache)
    {
        $this->_memcache = $memcache;
    }

    /**
     * 获取memcache
     *
     * @return Memcache
     */
    function getMemcache()
    {
        return $this->_memcache;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::_doSet()
     */
    protected function _doSet($key, $value, $duration)
    {
        return $this->_memcache->set($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::_doAdd()
     */
    protected function _doAdd($key, $value, $duration)
    {
        return $this->_memcache->add($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::_doGet()
     */
    protected function _doGet($key)
    {
        $val = $this->_memcache->get($key);
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
        return $this->_doGet($key) !== false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::_doDelete()
     */
    protected function _doDelete($key)
    {
        return $this->_memcache->delete($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::_doFlush()
     */
    protected function _doFlush()
    {
        return $this->_memcache->flush();
    }
}
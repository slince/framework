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
    private $memcached;

    function __construct(\Memcached $memcached)
    {
        $this->memcached = $memcached;
    }

    /**
     * 设置memcached
     *
     * @param \Memcached $memcached            
     */
    function setMemcached(\Memcached $memcached)
    {
        $this->memcache = $memcache;
    }

    /**
     * 获取memcache
     *
     * @return Memcache
     */
    function getMemcached()
    {
        return $this->memcached;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doSet()
     */
    protected function doSet($key, $value, $duration)
    {
        return $this->memcached->set($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doAdd()
     */
    protected function doAdd($key, $value, $duration)
    {
        return $this->memcached->add($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doGet()
     */
    protected function doGet($key)
    {
        $val = $this->memcached->get($key);
        if (is_null($val)) {
            $val = false;
        }
        return $val;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doExists()
     */
    protected function doExists($key)
    {
        return $this->get($key) !== false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doDelete()
     */
    protected function doDelete($key)
    {
        return $this->memcached->delete($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doFlush()
     */
    protected function doFlush()
    {
        return $this->memcached->flush();
    }
}
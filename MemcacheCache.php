<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

class MemcacheCache extends AbstractCache
{

    /**
     * memcache实例
     *
     * @var \Memcache
     */
    private $memcache;

    function __construct(\Memcache $memcache)
    {
        $this->memcache = $memcache;
    }

    /**
     * 设置memcache
     *
     * @param \Memcache $memcache            
     */
    function setMemcache(\Memcache $memcache)
    {
        $this->memcache = $memcache;
    }

    /**
     * 获取memcache
     *
     * @return Memcache
     */
    function getMemcache()
    {
        return $this->memcache;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doSet()
     */
    protected function doSet($key, $value, $duration)
    {
        return $this->memcache->set($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doAdd()
     */
    protected function doAdd($key, $value, $duration)
    {
        return $this->memcache->add($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doGet()
     */
    protected function doGet($key)
    {
        $val = $this->memcache->get($key);
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
        return $this->doGet($key) !== false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doDelete()
     */
    protected function doDelete($key)
    {
        return $this->memcache->delete($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doFlush()
     */
    protected function doFlush()
    {
        return $this->memcache->flush();
    }
}
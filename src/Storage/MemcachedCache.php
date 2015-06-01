<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache\Storage;

class MemcachedStorage extends AbstractStorage
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
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::set()
     */
    function set($key, $value, $duration)
    {
        return $this->_memcached->set($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\StorageInterface::add()
     */
    function add($key, $value, $duration)
    {
        return $this->_memcached->add($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::get()
     */
    function get($key)
    {
        return $this->_memcached->get($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::delete()
     */
    function delete($key)
    {
        return $this->_memcached->delete($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::exists()
     */
    function exists($key)
    {
        return $this->get($key) !== false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::flush()
     */
    function flush()
    {
        return $this->_memcached->flush();
    }
}
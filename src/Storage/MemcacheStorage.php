<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache\Storage;

class MemcacheStorage extends AbstractStorage
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
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::set()
     */
    function set($key, $value, $duration)
    {
        return $this->_memcache->set($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\StorageInterface::add()
     */
    function add($key, $value, $duration)
    {
        return $this->_memcache->add($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::get()
     */
    function get($key)
    {
        return $this->_memcache->get($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::delete()
     */
    function delete($key)
    {
        return $this->_memcache->delete($key);
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
        return $this->_memcache->flush();
    }
}
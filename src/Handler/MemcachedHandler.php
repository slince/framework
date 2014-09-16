<?php
/**
 * slince cache library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Cache\Handler;

class MemcachedHandler extends AbstractHandler
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
     * @see \Slince\Cache\HandlerInterface::set()
     */
    function set($key, $value, $duration)
    {
        return $this->_memcached->set($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\HandlerInterface::add()
     */
    function add($key, $value, $duration)
    {
        return $this->_memcached->add($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::get()
     */
    function get($key)
    {
        return $this->_memcached->get($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::delete()
     */
    function delete($key)
    {
        return $this->_memcached->delete($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::exists()
     */
    function exists($key)
    {
        return $this->get($key) !== false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::flush()
     */
    function flush()
    {
        return $this->_memcached->flush();
    }
}
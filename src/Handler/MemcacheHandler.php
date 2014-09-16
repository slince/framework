<?php
/**
 * slince cache library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Cache\Handler;

class MemcacheHandler extends AbstractHandler
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
     * @see \Slince\Cache\HandlerInterface::set()
     */
    function set($key, $value, $duration)
    {
        return $this->_memcache->set($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\HandlerInterface::add()
     */
    function add($key, $value, $duration)
    {
        return $this->_memcache->add($key, $value, false, time() + $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::get()
     */
    function get($key)
    {
        return $this->_memcache->get($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::delete()
     */
    function delete($key)
    {
        return $this->_memcache->delete($key);
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
        return $this->_memcache->flush();
    }
}
<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache\Handler;

class ApcHandler extends AbstractHandler
{

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::set()
     */
    function set($key, $value, $duration)
    {
        return apc_store($key, $value, $duration);
    }

    function add($key, $value, $duration)
    {
        return apc_add($key, $value, $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::get()
     */
    function get($key)
    {
        return apc_fetch($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::delete()
     */
    function delete($key)
    {
        return apc_delete($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::exists()
     */
    function exists($key)
    {
        return apc_exists($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::flush()
     */
    function flush()
    {
        apc_clear_cache('user');
    }
}
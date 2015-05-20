<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache\Storage;

class ApcStorage extends AbstractStorage
{

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::set()
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
     * @see \Slince\Cache\StorageInterface::get()
     */
    function get($key)
    {
        return apc_fetch($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::delete()
     */
    function delete($key)
    {
        return apc_delete($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::exists()
     */
    function exists($key)
    {
        return apc_exists($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::flush()
     */
    function flush()
    {
        apc_clear_cache('user');
    }
}
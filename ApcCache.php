<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

class ApcCache extends AbstractCache
{

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::doSet()
     */
    protected function doSet($key, $value, $duration)
    {
        return apc_store($key, $value, $duration);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\AbstractCache::doAdd()
     */
    protected function doAdd($key, $value, $duration)
    {
        return apc_add($key, $value, $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::doGet()
     */
    protected function doGet($key)
    {
        return apc_fetch($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::doDelete()
     */
    protected function doDelete($key)
    {
        return apc_delete($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::doExists()
     */
    protected function doExists($key)
    {
        return apc_exists($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::doFlush()
     */
    protected function doFlush()
    {
        apc_clear_cache('user');
    }
}
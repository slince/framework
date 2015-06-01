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
     * @see \Slince\Cache\StorageInterface::_doSet()
     */
    protected function _doSet($key, $value, $duration)
    {
        return apc_store($key, $value, $duration);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\AbstractCache::_doAdd()
     */
    protected function _doAdd($key, $value, $duration)
    {
        return apc_add($key, $value, $duration);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::_doGet()
     */
    protected function _doGet($key)
    {
        return apc_fetch($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::_doDelete()
     */
    protected function _doDelete($key)
    {
        return apc_delete($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::_doExists()
     */
    protected function _doExists($key)
    {
        return apc_exists($key);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::_doFlush()
     */
    protected function _doFlush()
    {
        apc_clear_cache('user');
    }
}
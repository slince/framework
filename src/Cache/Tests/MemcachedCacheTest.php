<?php
namespace Slince\Cache\Tests;

use Slince\Cache\MemcacheCached;

class MemcachedCacheTest extends CacheTestCase
{
    function createCacheHandler()
    {
        $memcache = new \Memcached();
        $memcache->addServer('127.0.0.1', 11211);
        return new MemcacheCache($memcache);
    }
}
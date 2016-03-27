<?php
namespace Slince\Cache\Tests;

use Slince\Cache\MemcacheCache;

class MemcacheCacheTest extends CacheTestCase
{
    function createCacheHandler()
    {
        $memcache = new \Memcache();
        $memcache->connect('127.0.0.1', 11211);
        return new MemcacheCache($memcache);
    }
}
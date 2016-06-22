<?php
namespace Slince\Cache\Tests;

use Slince\Cache\MemcacheCache;

class MemcacheCacheTest extends CacheTestCase
{
    function createCacheHandler()
    {
        $memcache = new \Memcache();
        $memcache->connect('192.168.20.21', 11211);
        return new MemcacheCache($memcache);
    }
}
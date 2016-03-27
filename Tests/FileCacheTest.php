<?php
namespace Slince\Cache\Tests;

use Slince\Cache\FileCache;

class FileCacheTest extends CacheTestCase
{

    protected $cachePath = __DIR__ . '/tmp';

    function createCacheHandler()
    {
        return new FileCache($this->cachePath);
    }
}
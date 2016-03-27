<?php
namespace Slince\Session\Tests\Storage;

use Slince\Session\Tests\SessionTestCase;
use Slince\Session\Storage\CacheStorage;
use Slince\Cache\FileCache;

class CacheStorageTest extends SessionTestCase
{
    protected $cacheDir = __DIR__  . '/../cache_session';

    protected function createStorage()
    {
        return new CacheStorage(new FileCache($this->cacheDir));
    }
}
<?php
use Slince\Cache\MemcacheCache;

class _memcacheCacheTest extends \PHPUnit_Framework_TestCase
{

    private $_memcacheCache;

    function setUp()
    {
        $memcache = new \Memcache();
        $memcache->connect('127.0.0.1', 11211);
        $this->_memcacheCache = new MemcacheCache($memcache);
    }

    function teerDown()
    {
        unset($this->_memcacheCache);
    }

    function testSet()
    {
        $res = $this->_memcacheCache->set('key1', 'value1');
        $this->assertTrue($res);
    }

    function testAdd()
    {
        $this->_memcacheCache->delete('key2');
        $res = $this->_memcacheCache->add('key2', 'value2');
        $this->assertTrue($res);
        $res = $this->_memcacheCache->add('key2', 'value2');
        $this->assertFalse($res);
    }

    function testGet()
    {
        $value = 'value3';
        $this->_memcacheCache->set('key3', $value);
        $val = $this->_memcacheCache->get('key3');
        $this->assertEquals($value, $val);
    }

    function testDelete()
    {
        $value = 'value4';
        $this->_memcacheCache->set('key4', $value);
        $val = $this->_memcacheCache->get('key4');
        $this->assertEquals($value, $val);
        $this->_memcacheCache->delete('key4');
        $val = $this->_memcacheCache->get('key4');
        $this->assertEmpty($val);
    }

    function testExists()
    {
        $value = 'value7';
        $this->_memcacheCache->set('key7', $value);
        $res = $this->_memcacheCache->exists('key7');
        $this->assertTrue($res);
        $this->_memcacheCache->delete('key7');
        $res = $this->_memcacheCache->exists('key7');
        $val = $this->_memcacheCache->get('key7');
        $this->assertFalse($res);
    }
    
    function testFlush()
    {
        $value = 'value5';
        $this->_memcacheCache->set('key5', $value);
        $this->_memcacheCache->set('key6', $value);
        $val1 = $this->_memcacheCache->get('key5');
        $val2 = $this->_memcacheCache->get('key6');
        $this->assertEquals($value, $val1);
        $this->assertEquals($value, $val2);
        $this->_memcacheCache->flush();
        $val1 = $this->_memcacheCache->get('key5');
        $val2 = $this->_memcacheCache->get('key6');
        $this->assertEmpty($val1);
        $this->assertEmpty($val2);
    }

}
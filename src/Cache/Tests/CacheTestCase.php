<?php
namespace Slince\Cache\Tests;

abstract class CacheTestCase extends \PHPUnit_Framework_TestCase
{
    protected $cacheHandler;

    abstract protected function createCacheHandler();

    function setUp()
    {
        $this->cacheHandler = $this->createCacheHandler();
    }

    function testSet()
    {
        $res = $this->cacheHandler->set('key1', 'value1');
        $this->assertTrue($res);
    }

    function testExist()
    {
        $this->cacheHandler->set('key1', 'value2');
        $res = $this->cacheHandler->exists('key1');
        $this->assertTrue($res);
    }

    function testAdd()
    {
        $res = $this->cacheHandler->add('key2', 'value2');
        $this->assertTrue($res);
        $res = $this->cacheHandler->add('key2', 'value2');
        $this->assertFalse($res);
        $this->cacheHandler->delete('key2');
    }

    function testGet()
    {
        $value = 'value3';
        $this->cacheHandler->set('key3', $value);
        $var = $this->cacheHandler->get('key3');
        $this->assertEquals($value, $var);
    }

    function testDelete()
    {
        $value = 'value4';
        $this->cacheHandler->set('key4', $value);
        $val = $this->cacheHandler->get('key4');
        $this->assertEquals($value, $val);
        $this->cacheHandler->delete('key4');
        $val = $this->cacheHandler->get('key4');
        $this->assertEmpty($val);
    }

    function testExists()
    {
        $value = 'value7';
        $this->cacheHandler->set('key7', $value);
        $res = $this->cacheHandler->exists('key7');
        $this->assertTrue($res);
        $this->cacheHandler->delete('key7');
        $res = $this->cacheHandler->exists('key7');
        $this->assertFalse($res);
    }

    function testFlush()
    {
        $value = 'value5';
        $this->cacheHandler->set('key5', $value);
        $this->cacheHandler->set('key6', $value);
        $val1 = $this->cacheHandler->get('key5');
        $val2 = $this->cacheHandler->get('key6');
        $this->assertEquals($value, $val1);
        $this->assertEquals($value, $val2);
        $this->cacheHandler->flush();
        $val1 = $this->cacheHandler->get('key5');
        $val2 = $this->cacheHandler->get('key6');
        $this->assertEmpty($val1);
        $this->assertEmpty($val2);
    }
}
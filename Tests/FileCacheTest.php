<?php
use Slince\Cache\FileCache;

class FileCacheTest extends \PHPUnit_Framework_TestCase
{

    private $_fileCache;

    function setUp()
    {
        $this->_fileCache = new FileCache(__DIR__ . '/tmp/', '.data');
    }
    function teerDown()
    {
        unset($this->_fileCache);
    }

    function testSet() 
    {
        $res = $this->_fileCache->set('key1', 'value1');
        $this->assertTrue($res);
    }
    
    function testExist() 
    {
        $this->_fileCache->set('key1', 'value2');
        $res = $this->_fileCache->exists('key1');
        $this->assertTrue($res);
    }

    function testAdd()
    {
        $res = $this->_fileCache->add('key2', 'value2');
        $this->assertTrue($res);
        $res = $this->_fileCache->add('key2', 'value2');
        $this->assertFalse($res);
        $this->_fileCache->delete('key2');
    }

    function testGet()
    {
        $value = 'value3';
        $this->_fileCache->set('key3', $value);
        $var = $this->_fileCache->get('key3');
        $this->assertEquals($value, $var);
    }

    function testDelete()
    {
        $value = 'value4';
        $this->_fileCache->set('key4', $value);
        $val = $this->_fileCache->get('key4');
        $this->assertEquals($value, $val);
        $this->_fileCache->delete('key4');
        $val = $this->_fileCache->get('key4');
        $this->assertEmpty($val);
    }

    function testExists()
    {
        $value = 'value7';
        $this->_fileCache->set('key7', $value);
        $res = $this->_fileCache->exists('key7');
        $this->assertTrue($res);
        $this->_fileCache->delete('key7');
        $res = $this->_fileCache->exists('key7');
        $this->assertFalse($res);
    }
    
    function testFlush()
    {
        $value = 'value5';
        $this->_fileCache->set('key5', $value);
        $this->_fileCache->set('key6', $value);
        $val1 = $this->_fileCache->get('key5');
        $val2 = $this->_fileCache->get('key6');
        $this->assertEquals($value, $val1);
        $this->assertEquals($value, $val2);
        $this->_fileCache->flush();
        $val1 = $this->_fileCache->get('key5');
        $val2 = $this->_fileCache->get('key6');
        $this->assertEmpty($val1);
        $this->assertEmpty($val2);
    }
    
    function test0Duration()
    {
        $this->_fileCache->set('key8', 'a', 0);
        $val = $this->_fileCache->get('key8');
        $this->assertNotEmpty($val);
    }

}
<?php
use Slince\Cache\FileCache;

class FileCacheTest extends \PHPUnit_Framework_TestCase
{

    private $_fixture;

    function setUp()
    {
        $this->_fixture = new FileCache(__DIR__ . '/tmp/');
    }
    function teerDown()
    {
        unset($this->_fixture);
    }

    function testSet() 
    {
        $res = $this->_fixture->set('key1', 'value1');
        $this->assertTrue($res);
    }

    function testAdd()
    {
        $res = $this->_fixture->add('key2', 'value2');
        $this->assertTrue($res);
        $res = $this->_fixture->add('key2', 'value2');
        $this->assertFalse($res);
    }

    function testGet()
    {
        $value = 'value3';
        $this->_fixture->set('key3', $value);
        $var = $this->_fixture->get('key3');
        $this->assertEquals($value, $var);
    }

    function testDelete()
    {
        $value = 'value4';
        $this->_fixture->set('key4', $value);
        $val = $this->_fixture->get('key4');
        $this->assertEquals($value, $val);
        $this->_fixture->delete('key4');
        $val = $this->_fixture->get('key4');
        $this->assertEmpty($val);
    }

    function testExists()
    {
        $value = 'value7';
        $this->_fixture->set('key7', $value);
        $res = $this->_fixture->exists('key7');
        $this->assertTrue($res);
        $this->_fixture->delete('key7');
        $res = $this->_fixture->exists('key7');
        $this->assertFalse($res);
    }
    
    function testFlush()
    {
        $value = 'value5';
        $this->_fixture->set('key5', $value);
        $this->_fixture->set('key6', $value);
        $val1 = $this->_fixture->get('key5');
        $val2 = $this->_fixture->get('key6');
        $this->assertEquals($value, $val1);
        $this->assertEquals($value, $val2);
        $this->_fixture->flush();
        $val1 = $this->_fixture->get('key5');
        $val2 = $this->_fixture->get('key6');
        $this->assertEmpty($val1);
        $this->assertEmpty($val2);
    }
    
    function test0Duration()
    {
        $value = $this->_fixture->get('a');
        if (empty($value)) {
            $this->_fixture->set('a', 'a', 0);
            echo 'test';
        }
    }

}
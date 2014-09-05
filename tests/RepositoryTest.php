<?php
use Slince\Config\Repository;
use Slince\Config\Parser\PhpArrayParser;
class RepositoryTest extends \PHPUnit_Framework_TestCase
{
    private $_fixture;
    function setUp()
    {
        $this->_fixture = new Repository();
    }
    function tearDown()
    {
        unset($this->_fixture);
    }
    function testKeyMap()
    {
        $this->assertAttributeInternalType('array', '_keyMap', $this->_fixture, 'keyMap must is array');
    }
    function testCount()
    {
        $this->_fixture->merge($this->getArray('config1'));
        $this->assertCount(1, $this->_fixture);
    }
    function testMerge()
    {
        $this->_fixture->merge($this->getArray('config1'));
        $this->assertCount(1, $this->_fixture);
        $this->_fixture->merge($this->getArray('config2'));
        $this->assertCount(2, $this->_fixture);
    }
    function testToArray()
    {
        $this->_fixture->merge($this->getArray('config3'));
        $array = $this->_fixture->toArray();
        $this->assertNotEmpty($array);
    }
    function testGet()
    {
        $this->_fixture->merge($this->getArray('config3'));
        $this->assertEquals($this->_fixture->get('key3'), 'value1');
        $this->assertEquals($this->_fixture['key3'], 'value1');
        $this->assertEmpty($this->_fixture->get('key6'));
        $this->assertEmpty($this->_fixture['key6']);
    }
    function testSet()
    {
        $this->_fixture->merge($this->getArray('config3'));
        $this->assertEquals($this->_fixture->get('key3'), 'value1');
        $this->_fixture->set('key3', 'value2');
        $this->assertEquals($this->_fixture->get('key3'), 'value2');
        $this->_fixture['key3'] = 'value3';
        $this->assertNotEquals($this->_fixture->get('key3'), 'value2');
    }
    function testExists()
    {
        $this->_fixture->merge($this->getArray('config3'));
        $this->assertTrue($this->_fixture->exists('key3'));
        $this->assertTrue(isset($this->_fixture['key3']));
        $this->assertFalse($this->_fixture->exists('key6'));
        $this->assertFalse(isset($this->_fixture['key6']));
    }
    function testUnset()
    {
        $this->_fixture->merge($this->getArray('config3'));
        $this->assertTrue($this->_fixture->exists('key3'));
        $this->assertTrue($this->_fixture->exists('key4'));
        $this->_fixture->delete('key3');
        $this->assertFalse($this->_fixture->exists('key3'));
        unset($this->_fixture['key4']);
        $this->assertFalse($this->_fixture->exists('key4'));
    }
    function testClear()
    {
        $this->_fixture->merge($this->getArray('config3'));
        $this->assertNotEmpty($this->_fixture->toArray());
        $this->_fixture->clear();
        $this->assertEmpty($this->_fixture->toArray());
    }
    function getArray($key = null)
    {
        $array =  array(
            'config1' => array(
                'key1' => 'value1',
            ),
            'config2' => array(
                'key1' => 'value1',
                'key2' => 'value2',
            ),
            'config3' => array(
                'key3' => 'value1',
                'key4' => 'value2',
                'key5' => 'value3',
            )
        );
        return is_null($key) ? $array : $array[$key];
    }
}
<?php
use Slince\Config\Repository;
use Slince\Config\Parser\IniParser;

class IniTest extends \PHPUnit_Framework_TestCase
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
    function testMerge()
    {
        $this->_fixture->merge(new IniParser(__DIR__ . '/config/config.ini'));
        $this->assertNotEmpty($this->_fixture->toArray());
    }
    
    function testException()
    {
        $this->setExpectedException('Slince\Config\Exception\ParseException');
        $this->_fixture->merge(new IniParser(__DIR__ . '/config/config2.ini'));
    }
    /**
     * @expectedException Slince\Config\Exception\ParseException
     */
    function testDump()
    {
        $this->_fixture->merge(new IniParser(__DIR__ . '/config/config.ini'));
        $this->_fixture['key5'] = 'value5';
        $this->_fixture->dump(new IniParser(__DIR__ . '/config/config-test.ini'));
    }
}
<?php
use Slince\Config\Repository;
use Slince\Config\File\IniFile;

class IniTest extends \PHPUnit_Framework_TestCase
{

    private $_config;

    function setUp()
    {
        $this->_config = new Repository();
    }

    function tearDown()
    {
        unset($this->_config);
    }

    function testMerge()
    {
        $this->_config->merge(new IniFile(__DIR__ . '/config/config.ini'));
        $this->assertNotEmpty($this->_config->getDataObject()->toArray());
    }

    function testException()
    {
        $this->setExpectedException('Slince\Config\Exception\ParseException');
        $this->_config->merge(new IniFile(__DIR__ . '/config/config2.ini'));
    }

    /**
     * @expectedException Slince\Config\Exception\ParseException
     */
    function testDump()
    {
        $this->_config->merge(new IniFile(__DIR__ . '/config/config.ini'));
        $this->_config->getDataObject()->set('key5', 'value5');
        $this->_config->getDataObject()->set('key6', 'value6');
        $this->setExpectedException('Slince\Config\Exception\ParseException');
        $this->_config->dump(new IniFile(__DIR__ . '/config/config-dump.ini'));
    }
}
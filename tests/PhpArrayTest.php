<?php
use Slince\Config\Repository;
use Slince\Config\File\PhpFile;

class PhpArrayTest extends \PHPUnit_Framework_TestCase
{
    private $_config;
    
    function setUp()
    {
        $this->_config = Repository::newInstance();
    }
    function tearDown()
    {
        unset($this->_config);
    }
    function testMerge()
    {
        $this->_config->merge(new PhpFile(__DIR__ . '/config/config.php'));
        $this->assertNotEmpty($this->_config->getDataObject());
        $this->_config->merge(new PhpFile(__DIR__ . '/config/config2.php'));
        $this->assertNotEmpty($this->_config->getDataObject());
    }
    
    function testException()
    {
        $this->setExpectedException('Slince\Config\Exception\ParseException');
        $this->_config->merge(new PhpFile(__DIR__ . '/config/config3.php'));
    }
    
    function testDump()
    {
        $this->_config->getDataObject()->flush();
        $this->_config->merge(new PhpFile(__DIR__ . '/config/config.php'));
        $this->_config->getDataObject()->set('key5', 'value5');
        $this->_config->getDataObject()->set('key6', 'value6');
        $this->_config->dump(new PhpFile(__DIR__ . '/config/config-dump.php'));
    }
}
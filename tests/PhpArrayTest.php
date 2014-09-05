<?php

use Slince\Config\Repository;
use Slince\Config\Parser\PhpArrayParser;

class PhpArrayTest extends \PHPUnit_Framework_TestCase
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
        $this->_fixture->merge(new PhpArrayParser(__DIR__ . '/config/config.php'));
        $this->assertCount(4, $this->_fixture);
        $this->_fixture->merge(new PhpArrayParser(__DIR__ . '/config/config2.php'));
        $this->assertCount(6, $this->_fixture);
    }
    
    function testException()
    {
        $this->setExpectedException('Slince\Config\Exception\ParseException');
        $this->_fixture->merge(new PhpArrayParser(__DIR__ . '/config/config3.php'));
    }
    
    function testDump()
    {
        $this->_fixture->merge(new PhpArrayParser(__DIR__ . '/config/config.php'));
        $this->_fixture['key5'] = 'value5';
        $this->_fixture->dump(new PhpArrayParser(__DIR__ . '/config/config-test.php'));
    }
}
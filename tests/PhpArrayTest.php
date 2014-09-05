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
    function testInit()
    {
        $this->_fixture->merge(new PhpArrayParser(__DIR__ . '/config/config.php'));
        $this->assertNotEmpty($this->_fixture);
        $this->assertCount(4, $this->_fixture);
    }
    function testRenew()
    {
        $this->_fixture->merge(new PhpArrayParser(__DIR__ . '/config/config.php'));
        $this->_fixture->renew(new PhpArrayParser(__DIR__ . '/config/config2.php'));
        $this->assertNotEmpty($this->_fixture);
        $this->assertCount(2, $this->_fixture);
        $this->assertContains('value5', $this->_fixture);
    }
    function testMerge()
    {
        $this->_fixture->merge(new PhpArrayParser(__DIR__ . '/config/config.php'));
        $this->_fixture->merge(new PhpArrayParser(__DIR__ . '/config/config2.php'));
        $this->assertNotEmpty($this->_fixture);
        $this->assertCount(6, $this->_fixture);
        $this->assertContains('value6', $this->_fixture);
    }
}
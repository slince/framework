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
    function testMerge()
    {
        $this->_fixture->merge(new PhpArrayParser(__DIR__ . '/config/config.php'));
        $this->assertCount(4, $this->_fixture);
        $this->_fixture->merge(new PhpArrayParser(__DIR__ . '/config/config2.php'));
        $this->assertCount(6, $this->_fixture);
    }
}
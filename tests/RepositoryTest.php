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
    function testInstance()
    {
        $this->assertInstanceOf('\ArrayAccess', $this->_fixture);    
    }
    /**
     * @group phparray
     */



}
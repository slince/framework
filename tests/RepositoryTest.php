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
    function testPhpArrayInit()
    {
        $this->_fixture->merge(new PhpArrayParser(__DIR__ . '/config/config.php'));
        $this->assertNotEmpty($this->_fixture);
        $this->assertCount(4, $this->_fixture);
    }
    /**
     * @group phparray
     */
    function testPhpArrayRenew()
    {
        $this->_fixture->merge(new PhpArrayParser(__DIR__ . '/config/config.php'));
        $this->_fixture->renew(new PhpArrayParser(__DIR__ . '/config/config2.php'));
        $this->assertNotEmpty($this->_fixture);
        $this->assertCount(2, $this->_fixture);
        $this->assertContains('value5', $this->_fixture);
    }
    /**
     * @group phparray
     */
    function testPhpArrayMerge()
    {
        $this->_fixture->merge(new PhpArrayParser(__DIR__ . '/config/config.php'));
        $this->_fixture->merge(new PhpArrayParser(__DIR__ . '/config/config2.php'));
        $this->assertNotEmpty($this->_fixture);
        $this->assertCount(6, $this->_fixture);
        $this->assertContains('value6', $this->_fixture);
    }

}
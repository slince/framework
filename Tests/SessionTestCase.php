<?php
/**
 * Session Test Casse
 */
namespace Slince\Session\Tests;

use Slince\Session\SessionManager;
use Slince\Session\Storage\FileStorage;
use Slince\Session\Storage\StorageInterface;

abstract class SessionTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @return FileStorage
     */
    abstract protected function createStorage();

    function setUp()
    {
        $this->storage = $this->createStorage();
        $this->sessionManager = new SessionManager($this->storage);
    }

    function tearDown()
    {
        //使用文件存储的结束之后要移除文件
        if ($this->storage instanceof FileStorage) {
            $this->storage->getFilesystem()->remove($this->storage->getSavePath());
        }
        $this->storage = null;
        $this->sessionManager = null;
    }

    function testStart()
    {
        $this->assertFalse($this->sessionManager->isStarted());
        $this->assertEquals('', $this->sessionManager->getId());
        $this->sessionManager->start();
        $id = $this->sessionManager->getId();
        $this->assertNotEquals('', $id);
        $this->assertEquals($id, $this->sessionManager->getId());
    }
    /**
     * @depends testStart
     */
    function testRegenerate()
    {
        $this->sessionManager->start();
        $id = $this->sessionManager->getId();
        $this->assertNotEquals('', $id);
        $this->sessionManager->regenerateId();
        $this->assertEquals($id, $this->sessionManager->getId());
    }

    function testName()
    {
        $name = $this->sessionManager->getName();
        $this->assertNotEquals('', $name);
        $newSessionName = 'HelloWorld';
        $this->sessionManager->setName($newSessionName);
        $this->assertEquals($newSessionName, $this->sessionManager->getName());
    }

    /**
     * @depends testStart
     */
    function testSetAndGet()
    {
        $this->sessionManager->start();
        $repository = $this->sessionManager->getRepository();
        $sessionKey = 'hello';
        $sessionValue = 'world';
        $repository[$sessionKey] = $sessionValue;
        $this->assertEquals($sessionValue, $repository->get($sessionKey));
        $repository->delete($sessionKey);
        $this->assertEquals(null, $repository->get($sessionKey));
    }

    function testDestroy()
    {
        $this->sessionManager->start();
        $repository = $this->sessionManager->getRepository();
        $sessionKey = 'hello';
        $sessionValue = 'world';
        $repository[$sessionKey] = $sessionValue;
        $this->assertEquals($sessionValue, $repository->get($sessionKey));
        $this->sessionManager->destroy();
        $this->assertEquals(null, $repository->get($sessionKey));
    }
}
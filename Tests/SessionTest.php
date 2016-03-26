<?php
/**
 * Session Test Casse
 */
use Slince\Session\SessionManager;
use Slince\Session\Storage\FileStorage;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SessionManager
     */
    protected $sessionManager;

    protected $storage;

    /**
     * @return FileStorage
     */
    protected function createStorage()
    {
        return new FileStorage(__DIR__ . '/tmp');
    }

    function setUp()
    {
        $this->storage = $this->createStorage();
        //使用文件存储的先创建目录
        if ($this->storage instanceof FileStorage) {
            $this->storage->getFilesystem()->mkdir($this->storage->getSavePath());
        }
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
        $this->assertEquals('', $this->sessionManager->getId());
        $this->sessionManager->start();
        $id = $this->sessionManager->getId();
        $this->assertNotEquals('', $id);
        $this->assertEquals($id, $this->sessionManager->getId());
    }

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
}
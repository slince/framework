<?php
namespace Slince\Session\Tests\Storage;

use Slince\Session\Tests\SessionTestCase;

use Slince\Session\Storage\FileStorage;

class FileStorageTest extends SessionTestCase
{
    protected $sessionDir = __DIR__  . '/../tmp_session';

    protected function createStorage()
    {
        return new FileStorage($this->sessionDir);
    }

    function testDestroy()
    {
        $this->sessionManager->start();
        $this->sessionManager->commit();
        $sessionFile = $this->storage->getSessionFile($this->sessionManager->getId());
        $this->assertFileExists($sessionFile);
        parent::testDestroy();
        $this->assertFileNotExists($sessionFile);
    }
}
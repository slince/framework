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

    function setUp()
    {
        parent::setUp();
        $this->sessionManager->start();
    }
}
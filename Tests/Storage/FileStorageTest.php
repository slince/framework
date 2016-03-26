<?php

use Slince\Session\Storage\FileStorage;

class FileStorageTest extends SessionTest
{
    protected $sessionDir = 'tmp_session';

    protected function createStorage()
    {
        return FileStorage($this->sessionDir);
    }

    function setUp()
    {
        parent::setUp();
        $this->sessionManager->start();
    }
}
<?php
namespace Slince\Session\Storage;

use Slince\Session\StorageInterface;
use Slince\Session\SessionManager;

class AbstractStorage implements StorageInterface
{
    function init(SessionManager $sessionManager)
    {
        session_set_save_handler($this);
    }
}
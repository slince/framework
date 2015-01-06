<?php
/**
 * slince session library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Session\Storage;

use Slince\Session\StorageInterface;
use Slince\Session\SessionManager;

class AbstractStorage implements StorageInterface
{

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Session\StorageInterface::init()
     */
    function init(SessionManager $sessionManager)
    {
        session_set_save_handler($this);
    }
}
<?php
/**
 * slince session library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Session\Storage;

use Slince\Session\SessionManager;

abstract class AbstractStorage implements StorageInterface
{
    
    /**
     * @param SessionManager $sessionManager
     * @see StorageInterface::init()
     */
    function initialize (SessionManager $sessionManager)
    {
        session_set_save_handler($this);
    }
}
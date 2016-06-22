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
     * SessionManager初始化工作
     * @param SessionManager $sessionManager
     */
    function initialize(SessionManager $sessionManager)
    {
        session_set_save_handler($this);
    }
}
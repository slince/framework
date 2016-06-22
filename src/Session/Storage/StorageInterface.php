<?php
/**
 * slince session library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Session\Storage;

use Slince\Session\SessionManager;

interface StorageInterface extends \SessionHandlerInterface
{

    /**
     * SessionManager初始化工作
     * @param SessionManager $sessionManager
     */
    function initialize (SessionManager $sessionManager);
}
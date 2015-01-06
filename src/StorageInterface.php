<?php
/**
 * slince session library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Session;

interface StorageInterface extends \SessionHandlerInterface
{

    /**
     * SessionManager初始化工作
     *
     * @param SessionManager $sessionManager            
     */
    function init(SessionManager $sessionManager);
}
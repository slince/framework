<?php
namespace Slince\Session\BridgeInterface;

interface BridgeInterface
{
    /**
     * SessionManager初始化工作
     * 
     * @param SessionManager $sessionManager
     */
    function init(SessionManager $sessionManager);
}
<?php
/**
 * slince session library
 * @author Tao <taosikai@yeah.net>
 */
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
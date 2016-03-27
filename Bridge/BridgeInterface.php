<?php
/**
 * slince session library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Session\Bridge;

interface BridgeInterface
{

    /**
     * SessionManager初始化工作
     *
     * @param SessionManager $sessionManager            
     */
    function initialize (SessionManager $sessionManager);
}
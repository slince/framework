<?php
/**
 * slince session library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Session\Bridge;

use Slince\Session\BridgeInterface\BridgeInterface;
use Slince\Session\SessionManager;

class QueryStringBridge extends VariableSourceBridge
{

    function __construct($queryVar = null)
    {
        if (! is_null($queryVar)) {
            parent::__construct($_GET[$queryVar]);
        }
    }

    function setQueryVar($queryVar)
    {
        $this->set($_GET[$queryVar]);
    }

    function init(SessionManager $sessionManager)
    {
        if (is_null($this->_variable)) {
            $this->setVariable($_GET[$sessionManager->getName()]);
        }
        parent::init($sessionManager);
    }
}
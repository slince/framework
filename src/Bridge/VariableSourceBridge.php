<?php
namespace Slince\Session\Bridge;

use Slince\Session\BridgeInterface\BridgeInterface;
use Slince\Session\SessionManager;

class VariableSourceBridge implements BridgeInterface
{
    protected $_variable;
    
    function __construct($variable)
    {
        $this->_variable = $variable;
    }
    
    function init(SessionManager $sessionManager)
    {
        $sessionManager->setId($this->_variable);
    }
    
    function setVariable($variable)
    {
        $this->_variable = $variable;
    }
    function getVariable()
    {
        return $this->_variable;
    }
}
<?php
namespace Slince\Session\Handler;

abstract class AbstractHandler implements \SessionHandlerInterface
{
    protected $_cacheHandler;
    
    function open()
    {
    }
    function write($session_id, $session_data)
    {
        return $this->_cacheHandler->set($session_id, $session_data);
    }
    
    function read($session_id)
    {
        return $this->_cacheHandler->get($session_id);
    }
    
    function destroy($session_id)
    {
        return $this->_cacheHandler->delete($session_id);
    }
    
    function gc($maxlifetime)
    {
        
    }
}
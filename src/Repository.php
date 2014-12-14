<?php
namespace Slince\Session;

class Repository implements \ArrayAccess, \Countable
{

    /**
     * session manager
     * 
     * @var SessionManager
     */
    private $_sessionManager;

    function __construct(SessionManager $sessionManager)
    {
        $this->_sessionManager = $sessionManager;
    }

    
    function exists($key)
    {
        if (! $this->_sessionManager->hasStarted()) {
            $this->_sessionManager->start();
        }
        return isset($_SESSION[$key]);
    }

    function delete($key)
    {
        if (! $this->_sessionManager->hasStarted()) {
            $this->_sessionManager->start();
        }
        return $_SESSION[$key];
    }

    function clear()
    {
        if (! $this->_sessionManager->hasStarted()) {
            $this->_sessionManager->start();
        }
        $_SESSION = [];
    }

    function set($key, $value)
    {
        if (! $this->_sessionManager->hasStarted()) {
            $this->_sessionManager->start();
        }
        $_SESSION[$key] = $value;
    }

    function get($key, $defaultValue = null)
    {
        if (! $this->_sessionManager->hasStarted()) {
            $this->_sessionManager->start();
        }
        return $this->exists($key) ? $_SESSION[$key] : $defaultValue;
    }

    /**
     * 实现接口方法
     *
     * @param mixed $offset            
     */
    function offsetUnset($offset)
    {
        $this->delete($offset);
    }

    /**
     * 实现接口方法
     *
     * @param mixed $offset            
     */
    function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    /**
     * 实现接口方法
     */
    function count()
    {
        return count($_SESSION);
    }
}
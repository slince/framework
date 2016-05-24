<?php
/**
 * slince session library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Session;

class Repository implements \ArrayAccess, \Countable
{

    /**
     * session manager
     *
     * @var SessionManager
     */
    protected $sessionManager;

    function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    /**
     * 判断session键值存不存在
     *
     * @param string $key            
     * @return boolean
     */
    function exists($key)
    {
        $this->checkAndActiveSession();
        return isset($_SESSION[$key]);
    }

    /**
     * 删除特定键值
     *
     * @param string $key            
     * @return boolean
     */
    function delete($key)
    {
        $this->checkAndActiveSession();
        unset($_SESSION[$key]);
    }

    /**
     * 清空键值
     */
    function clear()
    {
        $this->checkAndActiveSession();
        $_SESSION = [];
    }

    /**
     * 设置某一个session
     *
     * @param string $key            
     * @param mixed $value            
     */
    function set($key, $value)
    {
        $this->checkAndActiveSession();
        $_SESSION[$key] = $value;
    }

    /**
     * 获取某一个session
     *
     * @param string $key            
     * @param string $defaultValue            
     * @return mixed;
     */
    function get($key, $defaultValue = null)
    {
        $this->checkAndActiveSession();
        return $this->exists($key) ? $_SESSION[$key] : $defaultValue;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see ArrayAccess::offsetUnset()
     */
    function offsetUnset($offset)
    {
        $this->delete($offset);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see ArrayAccess::offsetExists()
     */
    function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see ArrayAccess::offsetGet()
     */
    function offsetGet($offset)
    {
        return $this->get($offset, null);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see ArrayAccess::offsetSet()
     */
    function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see Countable::count()
     */
    function count()
    {
        return count($_SESSION);
    }

    protected function checkAndActiveSession()
    {
        if (! $this->sessionManager->isStarted()) {
            $this->sessionManager->start();
        }
    }
}
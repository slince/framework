<?php
namespace Slince\Session;

use Slince\Session\Exception\SessionException;
use Slince\Session\BridgeInterface\BridgeInterface;

class SessionManager
{
    
    /**
     * handler
     * 
     * @var \SessionHandlerInterface
     */
    private $_handler;
    
    /**
     * 传递中介
     * 
     * @var BridgeInterface
     */
    private $_bridge;
    
    /**
     * 是否已经启动
     * 
     * @var boolean
     */
    private $_hasStarted = false;
    
    function setHandler(\SessionHandlerInterface $handler)
    {
        if ($this->hasStarted()) {
            $this->destroy();
        }
        $this->_handler = $handler;
    }
    
    function setBridge(BridgeInterface $bridge)
    {
        $this->_bridge = $bridge;
    }
    
    function setGcProbability($probability)
    {
        
    }
    
    /**
     * 启动或者重用会话
     */
    function start()
    {
        if (! $this->hasStarted()) {
            if (! is_null($this->_handler)) {
                session_set_save_handler($this->_handler, true);
            }
            session_start();
            $this->_hasStarted = true;
        }
    }
    
    /**
     * 是否已经启用
     */
    function hasStarted()
    {
        return $this->_hasStarted || $this->getStatus() == PHP_SESSION_ACTIVE;
    }
    
    function getRepository()
    {
        return new Repository($this);
    }
    
    /**
     * 获取会话状态
     * 
     * @return boolean
     */
    function getStatus()
    {
        return session_status();
    }
    
    /**
     * 删除已注册的所有变量
     * 
     * @return void
     */
    function clear()
    {
        session_unset();
    }
    
    /**
     * 销毁会话
     * 
     * @return boolean
     */
    function destroy()
    {
        session_destroy();
    }
    
    /**
     * 重新生成id
     * 
     * @return boolean
     */
    function regenerateId()
    {
        return session_regenerate_id();
    }
    
    /**
     * 读取会话名称
     */
    function getName()
    {
        return session_name();
    }
    

    /**
     * 设置名称
     * 
     * @param string $name
     * @throws SessionException
     * @return string
     */
    function setName($name)
    {
        if (! preg_match("/[A-z]/", $name)) {
            throw new SessionException('Session name contains at least one letter');
        }
        return session_name($name);
    }
    
    /**
     * 读取id
     * @return string
     */
    function getId()
    {
        return session_id();
    }
    
    /**
     * 设置id
     * @param unknown $id
     * @return string
     */
    function setId($id)
    {
        return session_id($id);
    }
    
    /**
     * 写入值
     * 
     * @return void
     */
    function commit()
    {
        return session_commit();
    }
}
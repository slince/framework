<?php
/**
 * slince session component
 * 
 * session组件是对session管理的抽象
 * 组件所有对session的设置都不会持久化到ini文件中
 * 同样组件对ini文件的读取是懒惰的；如果客户端没有主动
 * 修改session运行参数；组件不会主动从ini中读取默认参数
 * 
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Session;

use Slince\Session\Exception\SessionException;
use Slince\Session\BridgeInterface\BridgeInterface;

class SessionManager
{

    /**
     * storage
     *
     * @var \StorageInterface
     */
    private $_storage;

    /**
     * 传递中介
     *
     * @var BridgeInterface
     */
    private $_bridge;

    /**
     * session id
     * 
     * @var string
     */
    private $_id;

    /**
     * session name
     * 
     * @var string
     */
    private $_name;

    /**
     * gc时间
     *
     * @var int
     */
    private $_gcMaxLifeTime;

    /**
     * 是否已经启动
     *
     * @var boolean
     */
    private $_hasStarted = false;

    function setStorage(StorageInterface $storage)
    {
        if ($this->hasStarted()) {
            $this->destroy();
        }
        $this->_storage = $storage;
    }

    function setBridge(BridgeInterface $bridge)
    {
        $this->_bridge = $bridge;
    }

    /**
     * 启动或者重用会话
     */
    function start()
    {
        if (! $this->hasStarted()) {
            $this->_init();
            session_start();
            $this->_hasStarted = true;
        }
    }

    /**
     * 启动前调用
     */
    private function _init()
    {
        if (! is_null($this->_handler)) {
            session_set_save_handler($this->_handler, true);
        }
        // 初始化桥配置
        if (! is_null($this->_bridge)) {
            $this->_bridge->init($this);
        }
        // 初始化存储接口
        if (! is_null($this->_storage)) {
            $this->_storage->init($this);
        }
        // 设置session id;如果设置了id则不会再生成session文件
        if (! is_null($this->_id)) {
            session_id($this->_id);
        }
        // 自定义session；name
        if (! is_null($this->_name)) {
            session_name($this->_name);
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
        // 已经启动才能销毁
        if ($this->hasStarted()) {
            session_destroy();
        }
        $_SESSION = [];
        $this->_hasStarted = false;
    }

    /**
     * 重新生成id
     *
     * @return boolean
     */
    function regenerateId()
    {
        if (! $this->hasStarted()) {
            $this->start();
        }
        return session_regenerate_id();
    }

    /**
     * 读取会话名称
     */
    function getName()
    {
        return is_null($this->_name) ? session_name() : $this->_name;
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
        
        // session已经启动则销毁当前会话
        $this->destroy();
        return $this->_name = $name;
    }

    /**
     * 读取id
     * 
     * @return string
     */
    function getId()
    {
        return is_null($this->_id) ? session_id() : $this->_id;
    }

    /**
     * 设置id
     * 
     * @param unknown $id            
     * @return string
     */
    function setId($id)
    {
        $this->destroy();
        return $this->_id = $id;
    }

    /**
     * 获取gc周期
     *
     * @return int
     */
    function getGcMaxlifeTime()
    {
        if (is_null($this->_gcMaxLifeTime)) {
            $this->_gcMaxLifeTime = intval(ini_get('session.gc_maxlifetime'));
        }
        return $this->_gcMaxLifeTime;
    }

    /**
     * 写入值，session结束前调用
     *
     * @return void
     */
    function commit()
    {
        return session_commit();
    }
}
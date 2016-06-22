<?php
/**
 * slince session component
 *
 * session组件是对session管理的抽象
 * 组件所有对session的设置都不会持久化到ini文件中
 * 同样组件对ini文件的读取是懒惰的；如果客户端没有主动
 * 修改session运行参数；组件不会主动从ini中读取默认参数
 *
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Session;

use Slince\Session\Exception\SessionException;
use Slince\Session\Storage\StorageInterface;
use Slince\Session\Bridge\BridgeInterface;

class SessionManager
{

    /**
     * storage
     *
     * @var \StorageInterface
     */
    protected $storage;

    /**
     * 传递中介
     *
     * @var BridgeInterface
     */
    protected $bridge;

    /**
     * session id
     *
     * @var string
     */
    protected $id;

    /**
     * session name
     *
     * @var string
     */
    protected $name;

    /**
     * gc时间
     *
     * @var int
     */
    protected $gcMaxLifeTime;

    /**
     * 是否已经启动
     *
     * @var boolean
     */
    protected $started = false;

    function __construct(StorageInterface $storage = null, BridgeInterface $bridge = null)
    {
        if (!is_null($storage)) {
            $this->setStorage($storage);
        }
        if (!is_null($bridge)) {
            $this->setBridge($bridge);
        }
    }

    function setStorage(StorageInterface $storage)
    {
        if ($this->isStarted()) {
            $this->destroy();
        }
        $this->storage = $storage;
    }

    function setBridge(BridgeInterface $bridge)
    {
        $this->bridge = $bridge;
    }

    /**
     * 启动或者重用会话
     */
    function start()
    {
        if (! $this->isStarted()) {
            $this->initialize();
            session_start();
            $this->started = true;
        }
    }

    /**
     * 启动前调用
     */
    protected function initialize()
    {
        // 初始化桥配置
        if (!is_null($this->bridge)) {
            $this->bridge->initialize($this);
        }
        // 初始化存储接口
        if (!is_null($this->storage)) {
            $this->storage->initialize($this);
        }
        // 设置session id;如果设置了id则不会再生成session文件
        if (!is_null($this->id)) {
            session_id($this->id);
        }
        // 自定义session；name
        if (!is_null($this->name)) {
            session_name($this->name);
        }
    }

    /**
     * 是否已经启动session
     * @return boolean
     */
    function isStarted()
    {
        return $this->started || $this->getStatus() == PHP_SESSION_ACTIVE;
    }

    /**
     * 获取session存储参数
     * @return Repository
     */
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
        if ($this->isStarted()) {
            session_destroy();
        }
        $_SESSION = [];
        $this->started = false;
    }

    /**
     * 重新生成id
     *
     * @return boolean
     */
    function regenerateId()
    {
        if (! $this->isStarted()) {
            $this->start();
        }
        return session_regenerate_id();
    }

    /**
     * 读取会话名称
     */
    function getName()
    {
        return is_null($this->name) ? session_name() : $this->name;
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
        if (!preg_match("/[A-z]/", $name)) {
            throw new SessionException('Session name contains at least one letter');
        }
        // session已经启动则销毁当前会话
        $this->destroy();
        return $this->name = $name;
    }

    /**
     * 读取id
     *
     * @return string
     */
    function getId()
    {
        return is_null($this->id) ? session_id() : $this->id;
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
        return $this->id = $id;
    }

    /**
     * 获取gc周期
     *
     * @return int
     */
    function getGcMaxLifeTime()
    {
        if (is_null($this->gcMaxLifeTime)) {
            $this->gcMaxLifeTime = intval(ini_get('session.gc_maxlifetime'));
        }
        return $this->gcMaxLifeTime;
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
<?php
/**
 * slince session library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Session\Storage;

use Slince\Cache\CacheInterface;
use Slince\Session\SessionManager;

class CacheStorage extends AbstractStorage
{

    /**
     * @var CacheInterface
     */
    protected $cacheHandler;

    /**
     * 缓存时间
     * @var int
     */
    protected $duration;

    function __construct(CacheInterface $cacheHandler)
    {
        $this->setCacheHandler($cacheHandler);
    }

    /**
     * SessionManager初始化工作
     * @param SessionManager $sessionManager
     */
    function initialize(SessionManager $sessionManager)
    {
        $this->duration = $sessionManager->getGcMaxLifeTime();
        parent::initialize($sessionManager);
    }

    /**
     * 设置缓存句柄
     * @param CacheInterface $cacheHandler
     */
    function setCacheHandler(CacheInterface $cacheHandler)
    {
        $this->cacheHandler = $cacheHandler;
    }

    /**
     * 获取缓存句柄
     * @return CacheInterface
     */
    function getCacheHandler()
    {
        return $this->cacheHandler;
    }

    /**
     * 方法继承
     * @param string $savePath
     * @param string $sessionId
     * @return bool
     */
    function open($savePath, $sessionId)
    {
        $this->cacheHandler->setDuration($this->duration);
        return true;
    }

    /**
     * 方法继承
     * @param string $sessionId
     * @param string $sessionData
     * @return bool|void
     */
    function write($sessionId, $sessionData)
    {
        return $this->cacheHandler->set($sessionId, $sessionData);
    }

    /**
     * 方法继承
     * @param string $sessionId
     * @return string
     */
    function read($sessionId)
    {
        return $this->cacheHandler->get($sessionId);
    }

    /**
     * 方法继承
     * @param string $sessionId
     * @return bool|void
     */
    function destroy($sessionId)
    {
        return $this->cacheHandler->delete($sessionId);
    }

    /**
     * 方法继承
     * @param int $maxlifetime
     * @return bool
     */
    function gc($maxlifetime)
    {
        return true;
    }

    /**
     * 方法继承
     * @return bool
     */
    function close()
    {
        return true;
    }
}
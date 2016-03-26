<?php
/**
 * slince session library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Session\Handler;

use Slince\Cache\Cache;
use Slince\Session\SessionManager;

class CacheStorage extends AbstractStorage
{

    /**
     * cache handler
     * 
     * @var \Slince\Cache\Cache
     */
    protected $cacheHandler;

    /**
     * 缓存时间
     * 
     * @var int
     */
    protected $duration;

    function __construct(Cache $cacheHandler)
    {
        $this->cacheHandler = $cacheHandler;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see StorageInterface::init()
     */
    function init(SessionManager $sessionManager)
    {
        $this->duration = $sessionManager->getGcMaxlifeTime();
        parent::init($sessionManager);
    }

    /**
     * 设置缓存句柄
     * 
     * @param Cache $cacheHandler            
     */
    function setCacheHandler(Cache $cacheHandler)
    {
        $this->cacheHandler = $cacheHandler;
    }

    /**
     * 获取缓存句柄
     * 
     * @return \Slince\Cache\Cache
     */
    function getCacheHandler()
    {
        return $this->cacheHandler;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SessionHandlerInterface::open()
     */
    function open($savePath, $sessionId)
    {
        $this->cacheHandler->setDuration($this->duration);
        return true;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SessionHandlerInterface::write()
     */
    function write($sessionId, $sessionData)
    {
        return $this->cacheHandler->set($sessionId, $sessionData);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SessionHandlerInterface::read()
     */
    function read($sessionId)
    {
        return $this->cacheHandler->get($sessionId);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SessionHandlerInterface::destroy()
     */
    function destroy($sessionId)
    {
        return $this->cacheHandler->delete($sessionId);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SessionHandlerInterface::gc()
     */
    function gc($maxlifetime)
    {
        return true;
    }
}
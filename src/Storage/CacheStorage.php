<?php
/**
 * slince session library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Session\Handler;

use Slince\Session\StorageInterface;
use Slince\Cache\Cache;
use Slince\Session\SessionManager;

class CacheStorage implements StorageInterface
{

    /**
     * cache handler
     * 
     * @var Slince\Cache\Cache
     */
    private $_cacheHandler;

    /**
     * 缓存时间
     * 
     * @var int
     */
    private $_duration;

    function __construct(Cache $cacheHandler, $duration = null)
    {
        $this->_cacheHandler = $cacheHandler;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Session\StorageInterface::init()
     */
    function init(SessionManager $sessionManager)
    {
        $this->_duration = $sessionManager->getGcMaxlifeTime();
        parent::init($sessionManager);
    }

    /**
     * 设置缓存句柄
     * 
     * @param Cache $cacheHandler            
     */
    function setCacheHandler(Cache $cacheHandler)
    {
        $this->_cacheHandler = $cacheHandler;
    }

    /**
     * 获取缓存句柄
     * 
     * @return \Slince\Session\Handler\Slince\Cache\Cache
     */
    function getCacheHandler()
    {
        return $this->_cacheHandler;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SessionHandlerInterface::open()
     */
    function open($savePath, $sessionId)
    {
        $this->_cacheHandler->setDuration($this->_duration);
        return true;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SessionHandlerInterface::write()
     */
    function write($sessionId, $sessionData)
    {
        return $this->_cacheHandler->set($sessionId, $sessionData);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SessionHandlerInterface::read()
     */
    function read($sessionId)
    {
        return $this->_cacheHandler->get($sessionId);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SessionHandlerInterface::destroy()
     */
    function destroy($sessionId)
    {
        return $this->_cacheHandler->delete($sessionId);
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
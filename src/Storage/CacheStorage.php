<?php
namespace Slince\Session\Handler;

use Slince\Session\StorageInterface;
use Slince\Cache\Cache;
use Slince\Session\SessionManager;

class CacheStorage implements StorageInterface
{
    /**
     * cache handler
     * @var Slince\Cache\Cache
     */
    private $_cacheHandler;
    
    /**
     * 缓存时间
     * @var int
     */
    private $_duration;
    
    function __construct(Cache $cacheHandler, $duration = null)
    {
        $this->_cacheHandler = $cacheHandler;
    }
    
    function init(SessionManager $sessionManager)
    {
        $this->_duration = $sessionManager->getGcMaxlifeTime();
        parent::init($sessionManager);
    }
    
    function setCacheHandler(Cache $cacheHandler)
    {
        $this->_cacheHandler = $cacheHandler;
    }
    
    function getCacheHandler()
    {
        return $this->_cacheHandler;
    }
    
    function open($savePath, $sessionId)
    {
        $this->_cacheHandler->setDuration($this->_duration);
        return true;
    }
    function write($sessionId, $sessionData)
    {
        return $this->_cacheHandler->set($sessionId, $sessionData);
    }
    
    function read($sessionId)
    {
        return $this->_cacheHandler->get($sessionId);
    }
    
    function destroy($sessionId)
    {
        return $this->_cacheHandler->delete($sessionId);
    }
    
    function gc($maxlifetime)
    {
        return true;
    }
}
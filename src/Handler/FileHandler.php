<?php
namespace Slince\Session\Handler;

use Slince\Cache\Cache;
use Slince\Cache\Handler\FileHandler;

class FileHandler extends \SessionHandler
{
    private $_savePath;
    
    private $_cache;
    
    function __construct($savePath)
    {
        $this->_savePath = $savePath;
    }
    
    function open()
    {
        $this->_cache = new Cache(new FileHandler($this->_savePath));
        return true;
    }
    
    function write($session_id, $session_data)
    {
        return $this->_cache->set($session_id, $session_data);
    }
    function read($session_id)
    {
        
    }
}
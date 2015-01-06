<?php
namespace Slince\Session\Storage;

use Slince\Session\StorageInterface;
use Slince\Filesystem\File;
use Slince\Filesystem\Exception\FilesystemException;
use Slince\Filesystem\Directory;

class FileStorage implements StorageInterface
{
    private $_savePath;
    
    /**
     * file handler
     * @var Slince\Filesystem\File
     */
    private $_file;
    
    function __construct($savePath)
    {
        $this->_savePath = $savePath;
    }
    function open($savePath, $sessionName)
    {
        return true;
    }
    function read($sessionId)
    {
        try {
            return $this->getFileHandler($sessionId)->getContents();
        } catch (FilesystemException $e) {
            return '';
        }
    }
    function write($sessionDd, $sessionData)
    {
        try {
            return $this->getFileHandler($sessionId)->resave($sessionData);
        } catch (FilesystemException $e) {
            return false;
        }
    }
    function destroy($sessionId)
    {
        $this->getFileHandler($sessionId)->delete();
        return true;
    }
    
    function gc($maxlifetime)
    {
        $lists = (new Directory($this->_savePath))->lists();
        foreach ($lists as $list) {
            if ($list->getModifyTime() + $maxlifetime < time()) {
                $list->delete();
            }
        }
        return true;
    }
    
    function getFileHandler($sessionId)
    {
        if (is_null($this->_file)) {
            $this->_file = new File($this->_savePath . $sessionId);
        }
        return $this->_file;
    }
}
<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

use Slince\Filesystem\File;
use Slince\Filesystem\Directory;

class FileCache extends AbstractCache
{

    /**
     * 缓存位置
     *
     * @var string
     */
    private $_path;

    function __construct($path)
    {
        $this->_path = $path;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\StorageInterface::_doSet()
     */
    protected function _doSet($key, $value, $duration)
    {
        $file = new File($this->_getPath($key));
        $expire = ($duration == 0) ? 0 : time() + $duration;
        $str = $expire . "\r\n" . serialize($value);
        return $file->resave($str);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\StorageInterface::_doGet()
     */
    protected function _doGet($key)
    {
        $file = new File($this->_getPath($key));
        if ($file->isFile()) {
            list ($expire, $value) = explode("\r\n", $file->getContents());
            if ($expire == 0 || time() < $expire) {
                return @unserialize($value);
            } else {
                $file->delete();
            }
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\StorageInterface::_doDelete()
     */
    protected function _doDelete($key)
    {
        return @unlink($this->_getPath($key));
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\StorageInterface::_doExists()
     */
    protected function _doExists($key)
    {
        return file_exists($this->_getPath($key));
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\StorageInterface::_doFlush()
     */
    protected function _doFlush()
    {
        $directory = new Directory($this->_path);
        $directory->clear();
    }

    /**
     * 获取缓存文件路径
     *
     * @param string $key            
     * @return string
     */
    private function _getPath($key)
    {
        return $this->_path . md5($key);
    }
}
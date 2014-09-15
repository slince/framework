<?php
/**
 * slince cache library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Cache\Handler;

use Slince\Filesystem\File;
use Slince\Filesystem\Directory;

class FileHandler extends AbstractHandler
{

    private $_path;

    function __construct($path)
    {
        $this->_path = $path;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::set()
     */
    function set($key, $value, $duration)
    {
        $file = new File($this->_getPath($key));
        $str = (time() + $duration) . "\r\n" . serialize($value);
        return $file->resave($str);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::get()
     */
    function get($key)
    {
        $file = new File($this->_getPath($key));
        if ($file->isFile()) {
            list ($expire, $value) = explode("\r\n", $file->getContents());
            if (time() > $expire) {
                return $value;
            } else {
                $file->delete();
            }
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::delete()
     */
    function delete($key)
    {
        return @unlink($this->_getPath($key));
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\HandlerInterface::exists()
     */
    function exists($key)
    {
        return file_exists($this->_getPath($key));
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Cache\HandlerInterface::flush()
     */
    function flush()
    {
        $directory = new Directory($this->_path);
        return $directory->clear();
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
<?php
/**
 * slince cache library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Cache\Handler;

use Slince\Filesystem\File;

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
        $str = (time() + $duration) . "\r\n" . json_encode($value);
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
            $content = $file->getContents();
            list ($timestamp, $value) = explode("\r\n", $file->getContents());
            if (time() > $timestamp) {
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
        $file = new File($this->_getPath($key));
        return $file->delete();
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
     *
     * @param unknown $key            
     * @return string
     */
    private function _getPath($key)
    {
        return $this->_path . md5($key);
    }
}
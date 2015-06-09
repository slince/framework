<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

class FileCache extends AbstractCache
{

    /**
     * 缓存位置
     *
     * @var string
     */
    private $_path;

    /**
     * 缓存文件扩展名
     *
     * @var string
     */
    private $_ext;

    function __construct($path, $ext = '')
    {
        $this->setPath($path);
        $this->_ext = $ext;
    }

    /**
     * 设置缓存目录
     *
     * @param string $path            
     */
    function setPath($path)
    {
        $path = rtrim($path, '\\/') . '/';
        $this->_path = str_replace('\\', '/', $path);
    }

    /**
     * 获取缓存目录
     */
    function getPath()
    {
        return $this->_path;
    }

    /**
     * 设置缓存文件扩展名
     *
     * @param string $ext            
     */
    function setExt($ext)
    {
        $this->_ext = $ext;
    }

    /**
     * 获取缓存文件扩展名
     */
    function getExt()
    {
        return $this->_ext;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::_doSet()
     */
    protected function _doSet($key, $value, $duration)
    {
        $filePath = $this->_getFilePath($key);
        $expire = ($duration == 0) ? 0 : time() + $duration;
        $data = $expire . "\r\n" . serialize($value);
        return @file_put_contents($filePath, $data) !== false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::_doGet()
     */
    protected function _doGet($key)
    {
        $filePath = $this->_getFilePath($key);
        if (is_file($filePath)) {
            list ($expire, $value) = explode("\r\n", @file_get_contents($filePath));
            if ($expire == 0 || time() < $expire) {
                return @unserialize($value);
            } else {
                @unlink($filePath);
            }
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::_doExists()
     */
    protected function _doExists($key)
    {
        return file_exists($this->_getFilePath($key));
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::_doDelete()
     */
    protected function _doDelete($key)
    {
        return @unlink($this->_getFilePath($key));
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\StorageInterface::_doFlush()
     */
    protected function _doFlush()
    {
        foreach (glob("{$this->_path}*{$this->_ext}") as $filename) {
            @unlink($filename);
        }
    }

    /**
     * 获取缓存文件路径
     *
     * @param string $key            
     * @return string
     */
    private function _getFilePath($key)
    {
        return $this->_path . md5($key) . $this->_ext;
    }
}
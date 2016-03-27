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
    private $path;

    /**
     * 缓存文件扩展名
     *
     * @var string
     */
    private $ext;

    function _construct($path, $ext = '')
    {
        $this->setPath($path);
        $this->ext = $ext;
    }

    /**
     * 设置缓存目录
     *
     * @param string $path            
     */
    function setPath($path)
    {
        $path = rtrim($path, '\\/') . '/';
        $this->path = str_replace('\\', '/', $path);
    }

    /**
     * 获取缓存目录
     */
    function getPath()
    {
        return $this->path;
    }

    /**
     * 设置缓存文件扩展名
     *
     * @param string $ext            
     */
    function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * 获取缓存文件扩展名
     */
    function getExt()
    {
        return $this->ext;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doSet()
     */
    protected function doSet($key, $value, $duration)
    {
        $filePath = $this->getFilePath($key);
        $expire = ($duration == 0) ? 0 : time() + $duration;
        $data = $expire . "\r\n" . serialize($value);
        return @file_put_contents($filePath, $data) !== false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doGet()
     */
    protected function doGet($key)
    {
        $filePath = $this->getFilePath($key);
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
     * @see \Slince\Cache\AbstractStorage::doExists()
     */
    protected function doExists($key)
    {
        return file_exists($this->getFilePath($key));
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doDelete()
     */
    protected function doDelete($key)
    {
        return @unlink($this->getFilePath($key));
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Slince\Cache\AbstractStorage::doFlush()
     */
    protected function doFlush()
    {
        foreach (glob("{$this->path}*{$this->ext}") as $filename) {
            @unlink($filename);
        }
    }

    /**
     * 获取缓存文件路径
     *
     * @param string $key            
     * @return string
     */
    private function getFilePath($key)
    {
        return $this->path . md5($key) . $this->ext;
    }
}
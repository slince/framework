<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

use Slince\Cache\Exception\CacheException;

class FileCache extends AbstractCache
{

    /**
     * 缓存位置
     *
     * @var string
     */
    protected $path;

    /**
     * 缓存文件扩展名
     *
     * @var string
     */
    protected $ext;

    /**
     * 缓存文件名前缀，防止flush误删除
     *
     * @var string
     */
    protected $prefix = 'tmp_';

    function __construct($path, $ext = '')
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
        if (file_exists($path) && !@mkdir($path, 0777, true)) {
            throw new CacheException(sprintf('Path "%s could not create"', $path));
        }
        $this->path = $path;
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
     * @param string $ext
     */
    function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * 获取缓存文件扩展名
     * @return string
     */
    function getExt()
    {
        return $this->ext;
    }

    /**
     * 设置文件名前前缀
     * @param $prefix
     */
    function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * 获取缓存文件名前缀
     * @return string
     */
    function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * 设置一个变量，会覆盖已有变量
     * @param string $key
     * @param mixed $value
     * @param int $duration
     * @return boolean
     */
    protected function doSet($key, $value, $duration)
    {
        $filePath = $this->getFilePath($key);
        $expire = ($duration == 0) ? 0 : time() + $duration;
        $data = $expire . "\r\n" . serialize($value);
        return @file_put_contents($filePath, $data) !== false;
    }

    /**
     * 获取变量对应的值
     * @param string $key
     * @return array|null
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
     * 判断变量是否存在
     * @param string $key
     * @return boolean
     */
    protected function doExists($key)
    {
        return file_exists($this->getFilePath($key));
    }

    /**
     * 删除一个变量
     * @param string $key
     * @return boolean
     */
    protected function doDelete($key)
    {
        return @unlink($this->getFilePath($key));
    }

    /**
     * 清空所有存储变量
     * @return void
     */
    protected function doFlush()
    {
        foreach (glob("{$this->path}{$this->prefix}*{$this->ext}") as $filename) {
            @unlink($filename);
        }
    }

    /**
     * 获取缓存文件路径
     * @param string $key
     * @return string
     */
    protected function getFilePath($key)
    {
        return $this->path . $this->prefix . md5($key) . $this->ext;
    }
}
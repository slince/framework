<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

abstract class AbstractCache extends AbstractStorage implements CacheInterface
{

    /**
     * 默认的缓存时间
     *
     * @var int
     */
    protected $duration = 3600;

    /**
     * 设置默认的缓存时间
     *
     * @param int $duration            
     */
    function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * 获取默认的缓存时间
     *
     * @return int
     */
    function getDuration()
    {
        return $this->duration;
    }

    /**
     * 设置一个值
     *
     * @param string $key            
     * @param mixed $value            
     * @param int $duration            
     * @return boolean
     */
    function set($key, $value, $duration = null)
    {
        if (is_null($duration)) {
            $duration = $this->duration;
        }
        return $this->doSet($key, $value, $duration);
    }

    /**
     * 如果不存在则设置一个新值
     *
     * @param string $key            
     * @param mixed $value            
     * @param int $duration            
     * @return boolean
     */
    function add($key, $value, $duration = null)
    {
        if (is_null($duration)) {
            $duration = $this->duration;
        }
        return $this->doAdd($key, $value, $duration);
    }

    /**
     * 判断一个值是否存在
     *
     * @param string $key            
     * @return boolean
     */
    function exists($key)
    {
        return $this->doExists($key);
    }

    /**
     * 获取一个值
     *
     * @param string $key            
     * @param string $defaultValue            
     * @return mixed
     */
    function get($key)
    {
        return $this->doGet($key);
    }
    
    /**
     * 读取一个缓存，读取失败则创建
     *
     * @param string $key            
     * @param callable $create          
     * @param int $duration 
     * @return mixed
     */
    function read($key, $create, $duration = null)
    {
        $value = $this->get($key);
        if ($value === false) {
            $value = call_user_func($create);
            $this->set($key, $value, $duration);
        }
        return $value;
    }

    /**
     * 删除一个值
     *
     * @param string $key            
     * @return boolean
     */
    function delete($key)
    {
        return $this->doDelete($key);
    }

    /**
     * 清空储存区
     */
    function flush()
    {
        $this->doFlush();
    }

    protected function doAdd($key, $value, $duration)
    {
        if (!$this->doExists($key)) {
            return $this->doSet($key, $value, $duration);
        }
        return false;
    }
}
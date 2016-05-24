<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

use Memcache;

class MemcacheCache extends AbstractCache
{

    /**
     * memcache实例
     * @var Memcache
     */
    protected $memcache;

    function __construct(Memcache $memcache)
    {
        $this->memcache = $memcache;
    }

    /**
     * 设置memcache
     * @param Memcache $memcache
     */
    function setMemcache(Memcache $memcache)
    {
        $this->memcache = $memcache;
    }

    /**
     * 获取memcache
     * @return Memcache
     */
    function getMemcache()
    {
        return $this->memcache;
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
        return $this->memcache->set($key, $value, false, time() + $duration);
    }

    /**
     * 添加一个变量如果存在，则添加失败
     * @param string $key
     * @param mixed $value
     * @param int $duration
     * @return boolean
     */
    protected function doAdd($key, $value, $duration)
    {
        return $this->memcache->add($key, $value, false, time() + $duration);
    }

    /**
     * 获取变量对应的值
     * @param string $key
     * @return array|null
     */
    protected function doGet($key)
    {
        $val = $this->memcache->get($key);
        if (is_null($val)) {
            $val = false;
        }
        return $val;
    }

    /**
     * 判断变量是否存在
     * @param string $key
     * @return boolean
     */
    protected function doExists($key)
    {
        return $this->doGet($key) !== false;
    }

    /**
     * 删除一个变量
     * @param string $key
     * @return boolean
     */
    protected function doDelete($key)
    {
        return $this->memcache->delete($key);
    }

    /**
     * 清空所有存储变量
     * @return void
     */
    protected function doFlush()
    {
        return $this->memcache->flush();
    }
}
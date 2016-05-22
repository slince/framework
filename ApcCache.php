<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

class ApcCache extends AbstractCache
{
    /**
     * 设置一个变量，会覆盖已有变量
     * @param string $key
     * @param mixed $value
     * @param int $duration
     * @return boolean
     */
    protected function doSet($key, $value, $duration)
    {
        return apc_store($key, $value, $duration);
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
        return apc_add($key, $value, $duration);
    }

    /**
     * 获取变量对应的值
     * @param string $key
     * @return array|null
     */
    protected function doGet($key)
    {
        return apc_fetch($key);
    }

    /**
     * 删除一个变量
     * @param string $key
     * @return boolean
     */
    protected function doDelete($key)
    {
        return apc_delete($key);
    }

    /**
     * 判断变量是否存在
     * @param string $key
     * @return boolean
     */
    protected function doExists($key)
    {
        return apc_exists($key);
    }

    /**
     * 清空所有存储变量
     * @return void
     */
    protected function doFlush()
    {
        apc_clear_cache('user');
    }
}
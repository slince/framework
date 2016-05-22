<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

class ArrayCache extends AbstractCache
{

    /**
     * 缓存存储
     * @var array
     */
    protected $data = [];

    /**
     * 设置一个变量，会覆盖已有变量
     * @param string $key
     * @param mixed $value
     * @param int $duration
     * @return boolean
     */
    protected function doSet($key, $value, $duration)
    {
        $this->data[$key] = $value;
        return true;
    }

    /**
     * 获取变量对应的值
     * @param string $key
     * @return array|null
     */
    protected function doGet($key)
    {
        return $this->doExists($key) ? $this->data[$key] : false;
    }

    /**
     * 判断变量是否存在
     * @param string $key
     * @return boolean
     */
    protected function doExists($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * 删除一个变量
     * @param string $key
     * @return boolean
     */
    protected function doDelete($key)
    {
        unset($this->data[$key]);
        return true;
    }

    /**
     * 清空所有存储变量
     * @return void
     */
    protected function doFlush()
    {
        $this->data = [];
        return true;
    }
}
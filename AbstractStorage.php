<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

abstract class AbstractStorage
{

    /**
     * 获取变量对应的值
     *
     * @param string $key            
     * @return array|null
     */
    abstract protected function doGet($key);

    /**
     * 设置一个变量，会覆盖已有变量
     *
     * @param string $key            
     * @param mixed $value            
     * @param int $duration            
     * @return boolean
     */
    abstract protected function doSet($key, $value, $duration);

    /**
     * 添加一个变量如果存在，则添加失败
     *
     * @param string $key            
     * @param mixed $value            
     * @param int $duration            
     * @return boolean
     */
    abstract protected function doAdd($key, $value, $duration);

    /**
     * 删除一个变量
     *
     * @param string $key            
     * @return boolean
     */
    abstract protected function doDelete($key);

    /**
     * 判断变量是否存在
     *
     * @param string $key            
     * @return boolean
     */
    abstract protected function doExists($key);

    /**
     * 清空所有存储变量
     *
     * @return void
     */
    abstract protected function doFlush();
}
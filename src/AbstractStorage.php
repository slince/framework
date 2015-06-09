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
    abstract protected function _doGet($key);

    /**
     * 设置一个变量，会覆盖已有变量
     *
     * @param string $key            
     * @param mixed $value            
     * @param int $duration            
     * @return boolean
     */
    abstract protected function _doSet($key, $value, $duration);

    /**
     * 添加一个变量如果存在，则添加失败
     *
     * @param string $key            
     * @param mixed $value            
     * @param int $duration            
     * @return boolean
     */
    abstract protected function _doAdd($key, $value, $duration);

    /**
     * 删除一个变量
     *
     * @param string $key            
     * @return boolean
     */
    abstract protected function _doDelete($key);

    /**
     * 判断变量是否存在
     *
     * @param string $key            
     * @return boolean
     */
    abstract protected function _doExists($key);

    /**
     * 清空所有存储变量
     *
     * @return void
     */
    abstract protected function _doFlush();
}
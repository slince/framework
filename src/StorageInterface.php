<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

interface StorageInterface extends CacheInterface
{

    /**
     * 获取变量对应的值
     * 
     * @param string $key            
     * @return array|null
     */
    protected function _doGet($key);

    /**
     * 设置一个变量，会覆盖已有变量
     * 
     * @param string $key            
     * @param mixed $value            
     * @param int $duration            
     * @return boolean
     */
    protected function _doSet($key, $value, $duration);

    /**
     * 添加一个变量如果存在，则添加失败
     * 
     * @param string $key            
     * @param mixed $value            
     * @param int $duration            
     * @return boolean
     */
    protected function _doAdd($key, $value, $duration);

    /**
     * 删除一个变量
     * 
     * @param string $key            
     * @return boolean
     */
    protected function _doDelete($key);

    /**
     * 判断变量是否存在
     * 
     * @param string $key            
     * @return boolean
     */
    protected function _doExists($key);

    /**
     * 清空所有存储变量
     * 
     * @return void
     */
    protected function _doFlush();
}
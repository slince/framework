<?php
/**
 * slince cache library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Cache;

interface StorageInterface
{

    /**
     * 设置一个值
     *
     * @param string $key            
     * @param mixed $value            
     * @param int $duration            
     */
    function set($key, $value, $duration);

    /**
     * 添加一个值
     *
     * @param string $key            
     * @param mixed $value            
     * @param int $duration            
     */
    function add($key, $value, $duration);

    /**
     * 获取一个值
     *
     * @param string $key            
     */
    function get($key);

    /**
     * 删除一个值
     *
     * @param string $key            
     */
    function delete($key);

    /**
     * 判断一个值是否存在
     *
     * @param string $key            
     */
    function exists($key);

    /**
     * 清空储存区
     */
    function flush();
}
<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config;

interface ConfigInterface
{

    /**
     * 加载配置文件或者目录
     * 
     * @param string|array $path            
     */
    function load($path);

    /**
     * 解析一个配置文件或者配置目录
     * 
     * @param string|array $path            
     * @return array
     */
    function parse($path);

    /**
     * 将配置数据静态化到一个配置文件
     * 
     * @param string $filePath            
     * @return boolean
     */
    function dump($filePath);
}
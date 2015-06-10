<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config\File;

interface FileInterface
{

    /**
     * 设置文件路径
     *
     * @param string $path            
     */
    function setPath($path);

    /**
     * 获取文件路径
     */
    function getPath();

    /**
     * 获取文件路径，不检测地址
     */
    function getPathWithoutException();
}
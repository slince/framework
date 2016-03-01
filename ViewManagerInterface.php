<?php
/**
 * slince view component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View;

interface ViewManagerInterface
{

    /**
     * 设置视图位置
     * 
     * @param string $path            
     */
    function setViewPath($path);

    /**
     * 返回视图位置
     */
    function getViewPath();

    /**
     * 设置布局文件位置
     * 
     * @param string $path            
     */
    function setLayoutPath($path);

    /**
     * 返回布局位置
     */
    function getLayoutPath();

    /**
     * 获得视图文件对象
     */
    function load($name);
}
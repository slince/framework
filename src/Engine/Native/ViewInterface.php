<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

interface ViewInterface
{

    /**
     * 获取视图文件地址
     */
    function getViewFile();
    
    /**
     * 设置视图文件地址
     * 
     * @param string $viewFile
     */
    function setViewFile($viewFile);
}
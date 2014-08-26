<?php
/**
 * slince config library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Config;

interface ParserInterface
{

    /**
     * 解析对应的配置媒介
     * 
     * @return array
     */
    function parse();

    /**
     * 持久化配置数据
     *
     * @param array $keyMap            
     * @return boolean
     */
    function dump(array $keyMap);

    /**
     * 给解析器增加一个同类型的项目
     *
     * @param mixed $item            
     * @return void
     */
    function addItem($item);
}
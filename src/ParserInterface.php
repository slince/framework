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
     * 获取解析器的唯一标记
     */
    function getId();
}
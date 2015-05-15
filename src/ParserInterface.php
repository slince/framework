<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config;

interface ParserInterface
{

    /**
     * 解析对应的配置媒介
     * 
     * @return array
     */
    function parse(FileInterface $file);
}
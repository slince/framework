<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config\Parser;

use Slince\Config\File\FileInterface;

interface ParserInterface
{

    /**
     * 解析对应的配置媒介
     * 
     * @param string $filePath            
     * @return array
     */
    function parse($filePath);

    /**
     * 将数据持久化到配置文件
     *
     * @param string $filePath            
     * @param array $data            
     */
    function dump($filePath, array $data);

    /**
     * 获取解析器支持的文件扩展名
     */
    static function getSupportedExtensions();
}
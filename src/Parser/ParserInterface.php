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
     * @return array
     */
    function parse(FileInterface $file);

    /**
     * 将数据持久化到配置文件
     * 
     * @param FileInterface $file            
     * @param array $data            
     */
    function dump(FileInterface $file, array $data);
    
    /**
     * 获取解析器支持的文件扩展名
     */
    static function getSupportedExtensions();
}
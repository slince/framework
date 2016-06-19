<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config\Parser;

use Slince\Config\Exception\ParseException;

class IniParser extends AbstractParser
{

    /**
     * 解析对应的配置媒介
     * @param string $filePath
     * @throws ParseException
     * @return array
     */
    function parse($filePath)
    {
        if (($data = @parse_ini_file($filePath, true)) === false) {
            throw new ParseException(sprintf('The file "%s" has syntax errors', $filePath));
        } else {
            return $data;
        }
    }

    /**
     * 将数据持久化到配置文件
     * @param string $filePath
     * @param array $data
     * @throws ParseException
     * @return boolean
     */
    function dump($filePath, array $data)
    {
        throw new ParseException('Not supported');
    }
    
    /**
     * 获取解析器支持的文件扩展名
     * @return array
     */
    static function getSupportedExtensions()
    {
        return ['ini'];
    }
}
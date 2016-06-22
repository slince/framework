<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config\Parser;

use Slince\Config\Exception\ParseException;

class JsonParser extends AbstractParser
{

    /**
     * 解析对应的配置媒介
     * @param string $filePath
     * @throws ParseException
     * @return array
     */
    function parse($filePath)
    {
        $data = json_decode(file_get_contents($filePath), true);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new ParseException(sprintf('The file (%s)  need to contain a valid json string', $filePath));
        }
        return $data;
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
        $string = json_encode($data);
        return @file_put_contents($filePath, $string);
    }

    /**
     * 获取解析器支持的文件扩展名
     * @return array
     */
    static function getSupportedExtensions()
    {
        return ['json'];
    }
}
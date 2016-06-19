<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config\Parser;

use Slince\Config\Exception\ParseException;

class PhpParser extends AbstractParser
{

    /**
     * 解析对应的配置媒介
     * @param string $filePath
     * @throws ParseException
     * @return array
     */
    function parse($filePath)
    {
        $data = include $filePath;
        if (! is_array($data)) {
            throw new ParseException(sprintf('The file "%s" must return a PHP array', $filePath));
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
        $value = var_export($data, true);
$string = <<<EOT
<?php 
return {$value};
EOT;
        return @file_put_contents($filePath, $string) !== false;
    }

    /**
     * 获取解析器支持的文件扩展名
     * @return array
     */
    static function getSupportedExtensions()
    {
        return ['php'];
    }
}
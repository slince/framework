<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config;

class ParserFactory
{

    static function create($type)
    {
        $class = '';
        switch ($type) {
            case \Slince\Config\File\IniFile::FILE_TYPE:
                $class = '\\Slince\Config\Parser\\IniParser';
                break;
            case \Slince\Config\File\JsonFile::FILE_TYPE:
                $class = '\\Slince\Config\Parser\\JsonParser';
                break;
            case \Slince\Config\File\PhpFile::FILE_TYPE:
                $class = '\\Slince\Config\Parser\\PhpParser';
                break;
            default:
                throw new \Exception('Unsupported File Type');
        }
        return new $class();
    }
}
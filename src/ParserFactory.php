<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config;

use Slince\Config\File;
use Slince\Config\Parser;

class ParserFactory
{
    static function create($type)
    {
        $class = '';
        switch ($type) {
            case File\IniFile::FILE_TYPE:
                $class = 'Parser\\IniParser';
                break;
            case File\JsonFile::FILE_TYPE:
                $class = 'Parser\\JsonParser';
                break;
            case File\PhpFile::FILE_TYPE:
                $class = 'Parser\\PhpParser';
                break;
            default:
                throw new \Exception('Unsupported File Type');
        }
        return new $class();
    }
}
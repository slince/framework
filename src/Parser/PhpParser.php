<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config\Parser;

use Slince\Config\Exception\ParseException;
use Slince\Config\File\FileInterface;

class PhpParser extends AbstractParser
{

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Config\ParserInterface::parse()
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
     * (non-PHPdoc)
     * 
     * @see \Slince\Config\ParserInterface::dump()
     */
    function dump($filePath, array $data)
    {
        $string = "<?php\r\nreturn " . var_export($data, true) . ";\r\n";
        return @file_put_contents($filePath, $string) !== false;
    }
    /**
     * (non-PHPdoc)
     * @see \Slince\Config\Parser\ParserInterface::getSupportedExtensions()
     */
    static function getSupportedExtensions()
    {
        return ['php'];
    }
}
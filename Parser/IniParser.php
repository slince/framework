<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config\Parser;

use Slince\Config\Exception\ParseException;
use Slince\Config\File\FileInterface;

class IniParser extends AbstractParser
{

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Config\ParserInterface::parse()
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
     * (non-PHPdoc)
     * 
     * @see \Slince\Config\ParserInterface::dump()
     */
    function dump($filePath, array $data)
    {
        throw new ParseException('Not supported');
    }

    /**
     * (non-PHPdoc)
     * @see \Slince\Config\Parser\ParserInterface::getSupportedExtensions()
     */
    static function getSupportedExtensions()
    {
        return ['ini'];
    }
}
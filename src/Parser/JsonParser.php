<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config\Parser;

use Slince\Config\Exception\ParseException;
use Slince\Config\File\FileInterface;

class JsonParser extends AbstractParser
{

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Config\ParserInterface::parse()
     */
    function parse(FileInterface $file)
    {
        $data = json_decode(file_get_contents($file->getPath()), true);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new ParseException('The file (%s)  need to contain a valid json string');
        }
        return $data;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Config\ParserInterface::dump()
     */
    function dump(FileInterface $file, array $data)
    {
        $string = json_encode($data);
        return @file_put_contents($file->getPathWithoutException(), $string);
    }
}
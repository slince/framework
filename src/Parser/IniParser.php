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
    function parse(FileInterface $file)
    {
        if (($data = @parse_ini_file($file->getPath(), true)) === false) {
            throw new ParseException(sprintf('The file "%s" has syntax errors', $file->getPath()));
        } else {
            return $data;
        }
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Slince\Config\ParserInterface::dump()
     */
    function dump(FileInterface $file, array $data)
    {
        throw new ParseException('Not supported');
    }
}
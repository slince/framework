<?php
/**
 * slince config library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Config\Parser;

use Slince\Config\Exception\ParseException;

class PhpArrayParser extends AbstractParser
{
    /**
     * (non-PHPdoc)
     * @see \Slince\Config\Parser\AbstractParser::parse()
     */
    function parse()
    {
        $data = include $item;
        if (! is_array($data)) {
            throw ParseException(sprintf('The file "%s" must return a PHP array', $item));
        }
        return $data;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Config\Parser\AbstractParser::dump()
     */
    function dump($keyMap)
    {}
}
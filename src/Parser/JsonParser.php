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
     * (non-PHPdoc)
     * @see \Slince\Config\ParserInterface::parse()
     */
    function parse()
    {
        $data = json_decode(file_get_contents($this->_item), true);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new ParseException('The file (%s)  need to contain a valid json string');
        }
        return $data;
    }

    /**
     * (non-PHPdoc)
     * @see \Slince\Config\ParserInterface::dump()
     */
    function dump(array $keyMap)
    {
       $string = json_encode($keyMap);
       return @file_put_contents($this->_item, $string); 
    }
}
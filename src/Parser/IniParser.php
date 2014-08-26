<?php
/**
 * slince config library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Config\Parser;

use Slince\Config\Exception\ParseException;

class IniParser extends AbstractParser
{
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Config\Parser\AbstractParser::parse()
     */
    function parse()
    {
        if ($data = parse_ini_file($this->_item, true) === false) {
            throw ParseException(sprintf('The file "%s" has syntax errors', $item));
        } else {
            return $data;
        }
    }

    /**
     * (non-PHPdoc)
     * @see \Slince\Config\Parser\AbstractParser::dump()
     */
    function dump()
    {}
}
<?php
/**
 * slince config library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\Config\Parser;

use Slince\Config\Exception\ParseException;

class IniParser extends AbstractParser
{

    function parse()
    {
        if ($data = parse_ini_file($this->_item, true) === false) {
            throw ParseException(sprintf('The file "%s" has syntax errors', $item));
        } else {
            return $data;
        }
    }

    function dump()
    {}
}
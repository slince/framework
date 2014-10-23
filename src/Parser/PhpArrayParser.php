<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config\Parser;

use Slince\Config\Exception\ParseException;

class PhpArrayParser extends AbstractParser
{
    /**
     * (non-PHPdoc)
     * @see \Slince\Config\ParserInterface::parse()
     */
    function parse()
    {
        $data = include $this->_item;
        if (! is_array($data)) {
            throw new ParseException(sprintf('The file "%s" must return a PHP array', $this->_item));
        }
        return $data;
    }
    /**
     * (non-PHPdoc)
     * @see \Slince\Config\ParserInterface::dump()
     */
    function dump(array $keyMap)
    {
        $string = "<?php\r\nreturn " . var_export($keyMap, true) . ";\r\n";
        return @file_put_contents($this->_item, $string) !== false;
    }
}
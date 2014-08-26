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
     * 解析所有的条目
     *
     * @return array
     */
    function parse()
    {
        $data = include $item;
        if (! is_array($data)) {
            throw ParseException(sprintf('The file "%s" must return a PHP array', $item));
        }
        return $data;
    }

    function dump($keyMap)
    {}
}
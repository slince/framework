<?php
/**
 * slince config library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Config\Parser;

use Slince\Config\ParserInterface;

abstract class AbstractParser implements ParserInterface
{

    /**
     * 解析器条目
     * 
     * @var mixed
     */
    protected $_item;

    function __construct($item)
    {
        $this->_item = $item;
    }
}
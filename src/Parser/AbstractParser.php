<?php
/**
 * slince config library
 * @author Taosikai <taosikai@yeah.net>
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
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Config\ParserInterface::parse()
     */
    abstract function parse();
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Config\ParserInterface::dump()
     */
    abstract function dump(array $keyMap);
}
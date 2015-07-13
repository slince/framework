<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine;

abstract class AbstractEngine implements EngineInterface
{
    protected $_engine;
    
    function setEngine($engine)
    {
        $this->_engine = $engine;
    }
    
    function getEngine()
    {
        return $this->_engine;
    }
}
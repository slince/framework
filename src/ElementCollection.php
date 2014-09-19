<?php
/**
 * slince view library
 * @author Taosikai <taosikai@yeah.net>
 */
namespace Slince\View;

class ElementCollection
{

    private $_elements = [];

    function __construct(array $elements = [])
    {
        $this->_elements = $elements;
    }
    
    function add($element)
    {
        $this->_elements[] = $element;
    }
    
    function remove($element)
    {
        
    }
}
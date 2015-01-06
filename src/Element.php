<?php
namespace Slince\View;

class Element implements ViewFileInterface
{
    private $_path;
    
    function __construct($path)
    {
        $this->_path = $path;
    }
    
    function getViewFile()
    {
        return $this->_path;
    }
}
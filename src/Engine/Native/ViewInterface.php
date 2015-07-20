<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

interface ViewInterface
{

    function getViewFile();
    
    function setViewFile($viewFile);
}
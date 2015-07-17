<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

interface ViewRenderInterface
{
    function set($name, $value = null);
    
    function render(ViewInterface $viewFile);
}
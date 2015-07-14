<?php
/**
 * slince view component
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View;

interface ViewManagerInterface
{

    function setViewPath($path);

    function getViewPath();
}
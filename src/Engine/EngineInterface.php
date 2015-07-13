<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine;

use Slince\View\ViewFileInterface;

interface EngineInterface
{

    function setEngine($engine);

    function getEngine();

    function render(ViewFileInterface $view);
}
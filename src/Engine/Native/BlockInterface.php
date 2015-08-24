<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View\Engine\Native;

interface BlockInterface
{
    /**
     * 获取block内容
     */
    function getContent();
}
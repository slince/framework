<?php
namespace Slince\View;

class Factory
{

    static function createBlock($content)
    {
        return new Block($content);
    }

    static function createElement($path)
    {
        return new Element($path);
    }
}
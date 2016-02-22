<?php
namespace DefaultApplication;

use Slince\Application\Application;

class DefaultApplication extends Application
{

    protected $name = 'Default';

    function getRootPath()
    {
        return __DIR__ . '/../';
    }
    
    function initlize()
    {
    }
}
<?php
namespace DefaultApplication;

use Slince\Application\AbstractApplication;

class DefaultApplication extends AbstractApplication
{

    protected $name = 'Default';

    function getRootPath()
    {
        return __DIR__ . '/../';
    }
}
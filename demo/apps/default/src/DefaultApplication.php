<?php
namespace DefaultApplication;

use Slince\Application\AbstractApplication;

class DefaultApplication extends AbstractApplication
{
    function getRootPath()
    {
        return __DIR__ . '/../';
    }
}
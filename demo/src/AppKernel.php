<?php
use Slince\Application\Kernel;

class AppKernel extends Kernel
{

    function registerApplications()
    {
        $applications = [
            new Web\WebApp()
        ];
        return $applications;
    }
}
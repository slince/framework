<?php
namespace Slince\Application\Command;

use Slince\Console\Command as BaseCommand;
use Slince\Application\Kernel;

class Command extends BaseCommand
{
    /**
     * kernel
     * 
     * @var Kernel
     */
    protected $kernel;
    
    function __construct(Kernel $kernel)
    {
        parent::__construct();
        $this->kernel = $kernel;
    }
    /**
     * @return Kernel
     */
    function getKernel()
    {
        return $this->kernel;
    }
}

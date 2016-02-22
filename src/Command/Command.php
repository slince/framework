<?php
namespace Slince\Application\Command;

use Slince\Console\Command;
use Slince\Application\Kernel;

class Command extends Command
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

<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

abstract class AbstractApplication implements ApplicationInterface
{

    /**
     * kernel
     * 
     * @var Kernel
     */
    protected $kernel;
    
    /**
     * Get kernel
     * 
     * @return Kernel;
     */
    public function getKernel()
    {
        return $this->kernel; 
    }
    
    function registerService($container)
    {
        
    }
    
    function registerConfig($config)
    {
        
    }
    
    public function run(Kernel $kernel, $contollerName, $action, $parameters)
    {
        $this->kernel = $kernel;
        $controllerClass = $this->getControllerClass($contollerName);
        $controller = $this->getKernel()->getContainer()->create($controllerClass, [$this]);
        if (empty($controller)) {
            throw new MissControllerException($controllerName);
        }
        if(! $controller instanceof Controller) {
            throw new LogicException('Controller action can only return an instance of Response');
        }
        if (! method_exists($controller, $action)) {
            throw new MissActionException($controller, $action);
        }
        return $controller->invokeAction($action, $parameters);
    }

    protected function getControllerClass($contollerName)
    {
        $namespace = dirname(get_class($this));
        return "{$namespace}\\Controller\\{$contollerName}";
    }
}
<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

abstract class AbstractApplication implements ApplicationInterface
{

    /**
     * application name
     * 
     * @var string
     */
    protected $name;
    
    /**
     * 命令空间
     * 
     * @var 
     */
    protected $namespace;
    
    /**
     * kernel
     * 
     * @var Kernel
     */
    protected $kernel;
    

    /**
     * (non-PHPdoc)
     * @see \Slince\Application\ApplicationInterface::getName()
     */
    function getName()
    {
        return $this->name;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Application\ApplicationInterface::setName()
     */
    function setName($name)
    {
        $this->name = $name;
    }
    /**
     * (non-PHPdoc)
     * @see \Slince\Application\ApplicationInterface::getNamespace()
     */
    function getNamespace()
    {
        if (is_null($this->namespace)) {
            $this->namespace = dirname(get_class($this));
        }
        return $this->namespace;
    }
    /**
     * Get kernel
     * 
     * @return Kernel;
     */
    public function getKernel()
    {
        return $this->kernel; 
    }
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Application\ApplicationInterface::run()
     */
    public function run(Kernel $kernel, $controller, $action, $parameters)
    {
        $this->kernel = $kernel;
        $controllerClass = $this->getControllerClass($controller);
        $controllerInstance = $this->getKernel()->getContainer()->create($controllerClass, [$this]);
        if (empty($controllerInstance)) {
            throw new MissControllerException($controller);
        }
        if (! method_exists($controllerInstance, $action)) {
            throw new MissActionException($controller, $action);
        }
        return $controllerInstance->invokeAction($action, $parameters);
    }

    /**
     * 获取完整的controller class
     * 
     * @param string $controller
     * @return string
     */
    protected function getControllerClass($controller)
    {
        $namespace = $this->getNamespace();
        return "{$namespace}\\Controller\\{$controller}";
    }
}
<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

use Slince\Config\Config;
use Slince\Di\Container;
use Slince\Event\Dispatcher;
use Slince\Application\Exception\MissActionException;

abstract class Application implements ApplicationInterface
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
     * 当前执行的controller
     * 
     * @var Controller
     */
    protected $controller;
    
    protected $theme = false;
    
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
            $this->namespace = strstr(get_class($this), '\\', true);
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
        $this->initalize();
        $controllerClass = $this->getControllerClass($controller);
        $this->controller = $this->kernel->getContainer()->create($controllerClass, [$this]);
        
        if (empty($this->controller)) {
            throw new MissControllerException($controller);
        }
        if (! method_exists($this->controller, $action)) {
            throw new MissActionException($controller, $action);
        }
        return $this->controller->invokeAction($action, $parameters);
    }
    
    /**
     * 获取controller
     * 
     * @return \Slince\Application\Controller
     */
    function getController()
    {
        return $this->controller;
    }
    
    /**
     * 获取application的跟目录
     * 
     * @return string
     */
    abstract function getRootPath();

    /**
     * 设置theme
     * 
     * @param string $theme
     */
    function setTheme($theme)
    {
        $this->theme = $theme;
    }
    
    /**
     * 获取theme
     * 
     * @return boolean|string
     */
    function getTheme()
    {
        return $this->theme;
    }
    
    /**
     * 获取view path
     * 
     * @return string
     */
    function getViewPath()
    {
        if ($this->theme === false) {
            $path = $this->getRootPath() . 'views/';
        } else {
            $path = $this->getRootPath() . "themes/{$this->theme}/";
        }
        return $path;
    }
    
    /**
     * 初始化application
     */
    protected function initalize()
    {
        $this->registerConfigs($this->kernel->getContainer()->get('config'));
        $this->registerServices($this->kernel->getContainer());
        $this->registerSubscribers($this->kernel->getContainer()->get('dispatcher'));
    }
    
    /**
     * 注册service
     * @param Container $container
    */
    public function registerServices(Container $container)
    {}
    
    /**
     * 注册config
     * @param Config $config
    */
    public function registerConfigs(Config $config)
    {}
    
    /**
     * 注册subscriber
     *
     * @param Dispatcher $dispatcher
    */
    public function registerSubscribers(Dispatcher $dispatcher)
    {}
    
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
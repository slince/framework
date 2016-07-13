<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

use Cake\Utility\Inflector;
use Slince\Config\Config;
use Slince\Di\Container;
use Slince\Event\Dispatcher;
use Slince\Application\Exception\MissActionException;
use Slince\View\Engine\Native\ViewManager;

abstract class Application implements ApplicationInterface
{
    /**
     * application name
     * @var string
     */
    protected $name;
    
    /**
     * 命令空间
     * @var
     */
    protected $namespace;

    /**
     * application所在的根目录
     * 注意与kernel的root path
     * @var string
     */
    protected $rootPath;

    /**
     * kernel
     * @var Kernel
     */
    protected $kernel;
    
    /**
     * 当前执行的controller
     * @var Controller
     */
    protected $controller;

    /**
     * application使用的主题，false表示不启用
     * 主题功能
     * @var false|string
     */
    protected $theme = false;

    /**
     * view manager
     * @var ViewManager
     */
    protected $viewManager;

    /**
     * 获取application名称
     * @return string
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * 设置application名称
     * @param string $name
     */
    function setName($name)
    {
        $this->name = $name;
    }

    /**
     * 获取当前application的命名空间
     * @return string
     */
    function getNamespace()
    {
        if (is_null($this->namespace)) {
            $this->namespace = strstr(get_class($this), '\\', true);
        }
        return $this->namespace;
    }

    /**
     * 获取application的跟目录
     * @return string
     */
    public function getRootPath()
    {
        if (is_null($this->rootPath)) {
            $reflection = new \ReflectionObject($this);
            $this->rootPath = dirname(dirname($reflection->getFileName()));
        }
        return $this->rootPath;
    }

    /**
     * 获取view path
     * @return string
     */
    public function getViewPath()
    {
        if ($this->theme === false) {
            $path = $this->getRootPath() . '/views';
        } else {
            $path = $this->getRootPath() . "/themes/{$this->theme}";
        }
        return $path;
    }

    /**
     * Get kernel
     * @return Kernel;
     */
    public function getKernel()
    {
        return $this->kernel; 
    }

    /**
     * application启动
     * @param Kernel $kernel
     * @param string $controller
     * @param string $action
     * @param array $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws MissControllerException
     */
    public function run(Kernel $kernel, $controller, $action, $parameters)
    {
        $this->kernel = $kernel;
        $this->initialize();
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
     * @return Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * 设置theme
     * @param string $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }
    
    /**
     * 获取theme
     * @return boolean|string
     */
    public function getTheme()
    {
        return $this->theme;
    }
    
    /**
     * 初始化application
     */
    protected function initialize()
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
     * @param string $controller
     * @return string
     */
    protected function getControllerClass($controller)
    {
        $namespace = $this->getNamespace();
        return "{$namespace}\\Controller\\{$controller}";
    }

    /**
     * 获取ViewManager
     * @return ViewManager
     */
    public function getViewManager()
    {
        if (is_null($this->viewManager)) {
            $controller = $this->getKernel()->getParameter('controller');
            $controllerDir = Inflector::tableize(substr($controller, 0, -10));
            $viewManager = $this->getKernel()->getContainer()->get('view');
            $viewManager->setViewPath($this->getViewPath() . "/templates/{$controllerDir}/");
            $viewManager->setLayoutPath($this->getViewPath() . '/layouts/');
            $this->viewManager = $viewManager;
        }
        return $this->viewManager;
    }
}
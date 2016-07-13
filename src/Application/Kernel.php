<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

use Slince\Config\Repository;
use Slince\Di\Container;
use Slince\Event\Dispatcher;
use Slince\Event\Event;
use Slince\Application\Exception\LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Slince\Config\Config;
use Slince\Routing\Router;
use Slince\Routing\RequestContext;
use Slince\Routing\RouteBuilder;

abstract class Kernel
{
    /**
     * Container Instance
     * @var Container
     */
    protected $container;

    /**
     * 注册的application
     * @var array
     */
    protected $applications = [];

    /**
     * 被调度的application
     * @var ApplicationInterface
     */
    protected $application;

    /**
     * 是否是debug模式
     * @var boolean
     */
    protected $debug;

    /**
     * 命令空间
     * @var
     */
    protected $namespace;

    /**
     *  项目根目录
     * @var string
     */
    protected $rootPath;

    /**
     *  当前核心实例
     * @var Kernel
     */
    protected static $kernel;

    public function __construct($debug = false)
    {
        $this->debug = $debug;
        $this->rootPath = $this->getRootPath();
        $this->initialize();
        $this->dispatchEvent(EventStore::KERNEL_INITED);
        static::$kernel = $this;
    }

    /**
     * 初始化kernel
     */
    protected function initialize()
    {
        $this->registerErrorHandler();
        $this->container = $this->createContainer();
        $this->registerServices($this->container);
        $this->registerConfigs($this->container->get('config'));
        $this->registerEvents($this->container->get('dispatcher'));
        $this->registerRoutes($this->container->get('router')->getRouteBuilder());
        //注册所有的桥
        $this->initializeBridges();
        $this->initializeApplications();
    }

    /**
     * 注册错误和异常的捕获事件
     * return void
     */
    protected function registerErrorHandler()
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline = '', $errcontext = '') {
            $this->dispatchEvent(EventStore::ERROR_OCCURRED, func_get_args());
        });
        set_exception_handler(function (\Exception $excetion) {
            $this->dispatchEvent(EventStore::EXCEPTION_OCCURRED, ['exception' => $excetion]);
        });
        register_shutdown_function(function () {
            if ($error = error_get_last()) {
                $this->dispatchEvent(EventStore::ERROR_OCCURRED, $error);
            }
        });
    }

    /**
     * 实例化注册的application
     * @return void
     */
    protected function initializeApplications()
    {
        foreach ($this->registerApplications() as $application) {
            $this->applications[$application->getName()] = $application;
        }
    }

    /**
     * 实例化桥工作
     * @return void
     */
    protected function initializeBridges()
    {
        foreach ($this->registerBridges() as $bridge) {
            $bridge->initialize($this->getContainer());
        }
    }

    /**
     *  注册所有的bridge
     * @return array
     */
    abstract public function registerBridges();

    /**
     * 注册所有application
     * @return array
     */
    abstract public function registerApplications();

    /**
     * 注册service
     * @param Container $container
     */
    abstract public function registerServices(Container $container);

    /**
     * 注册config
     * @param Config $config
     */
    abstract public function registerConfigs(Config $config);

    /**
     * 注册route
     *
     * @param RouteBuilder $routes
     */
    abstract public function registerRoutes(RouteBuilder $routes);

    /**
     * 注册事件监听
     *
     * @param Dispatcher $dispatcher
     */
    abstract public function registerEvents(Dispatcher $dispatcher);
    
    /**
     * 获取DI容器
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * 获取router
     * @return Router
     */
    public function getRouter()
    {
        return $this->container->get('router');
    }

    /**
     * 是否工作在debug模式下
     * @return boolean
     */
    public function debug()
    {
        return $this->debug;
    }

    /**
     * 运行项目
     */
    public function run()
    {
        $request = Request::createFromGlobals();
        $response = $this->handleRequest($request);
        $this->sendResponse($response);
    }

    /**
     * 发送response到客户端
     * @param Response $response
     */
    public function sendResponse(Response $response)
    {
        $response->send();
        exit();
    }

    /**
     * 开始处理请求
     * @param Request $request
     * @return Response
     */
    public function handleRequest(Request $request)
    {
        $this->setParameter('request', $request);
        //绑定Request到RequestContext
        $context = $this->bindRequestToContext($request, RequestContext::create());
        $this->getRouter()->setContext($context);
        $route = $this->getRouter()->match($request->getPathInfo());
        $this->setParameter('route', $route); //匹配出来的route存入核心缓存
        //request匹配完毕，待派发
        $this->dispatchEvent(EventStore::PROCESS_REQUEST, [
            'request' => $request,
            'route' => $route
        ]);
        $action = $route->getAction();
        if (is_callable($action)) {
            $response = $this->runCallableAction($action, $route->getParameters());
        } else {
            list($applicationName, $controller, $action) = explode('@', $action);
            $this->setParameter('application', $applicationName);
            $this->setParameter('controller', $controller);
            $this->setParameter('action', $action);
            $response = $this->runApplication($applicationName, $controller, $action, $route->getParameters());
        }
        return $response;
    }

    /**
     * 核心内存缓存参数读取
     * @param string $name
     * @param mixed $default
     */
    public function getParameter($name, $default = null)
    {
        return $this->container->get('kernelCache')->get($name, $default);
    }

    /**
     * 核心内存缓存设置
     * @param string $name
     * @param mixed $value
     */
    public function setParameter($name, $value)
    {
        $this->container->get('kernelCache')->set($name, $value);
    }

    /**
     * 派发事件
     * @param string $eventName
     * @param array $parameters
     */
    public function dispatchEvent($eventName, array $parameters = [])
    {
        $event = new Event($eventName, $this, $parameters);
        $this->container->get('dispatcher')->dispatch($eventName, $event);
    }

    /**
     * @param $name
     * @return ApplicationInterface
     */
    public function getApplication($name)
    {
        return isset($this->applications[$name]) ? $this->applications[$name] : null;
    }

    /**
     * 获取当前正在运行的application
     * @return ApplicationInterface
     */
    function getDispatchedApplication()
    {
        return $this->application;
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
     * 获取项目root path
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
     *  配置文件目录
     * @return string
     */
    public function getConfigPath()
    {
        return $this->getRootPath() . '/config';
    }

    /**
     * 日志文件目录
     * @return string
     */
    public function getLogPath()
    {
        return $this->getRootPath() . '/tmp/logs';
    }

    /**
     *  缓存文件目录
     * @return string
     */
    public function getCachePath()
    {
        return $this->getRootPath() . '/tmp/cache';
    }

    /**
     * 调度回调
     * @param string $action
     * @param array $parameters
     * @throws LogicException
     * @return Response
     */
    protected function runCallableAction($action, $parameters = [])
    {
        $response = call_user_func($action, $parameters);
        if (!$response instanceof Response) {
            throw new LogicException("Route action must return a response instance");
        }
        return $response;
    }

    /**
     * 运行application
     * @param string $name
     * @param string $controller
     * @param string $action
     * @param array $parameters
     * @throws LogicException
     */
    protected function runApplication($name, $controller, $action, $parameters = [])
    {
        if (!isset($this->applications[$name])) {
            throw new LogicException("Application {$name} is not fund");
        }
        $this->application = $this->applications[$name];
        return $this->application->run($this, $controller, $action, $parameters);
    }

    /**
     * 绑定request到routing的context
     * @param Request $request
     * @param RequestContext $context
     * @return RequestContext
     */
    protected function bindRequestToContext(Request $request, RequestContext $context)
    {
        $context->setBaseUrl($request->getBaseUrl());
        $context->setPathInfo($request->getPathInfo());
        $context->setMethod($request->getMethod());
        $context->setHost($request->getHost());
        $context->setScheme($request->getScheme());
        $context->setHttpPort($request->getPort());
        $context->setHttpPort($request->isSecure() ? null : $request->getPort());
        $context->setHttpsPort($request->isSecure() ? $request->getPort() : null);
        $context->setQueryString($request->server->get('QUERY_STRING', ''));
        return $context;
    }

    /**
     * 创建DI容器
     * @return Container
     */
    protected function createContainer()
    {
        return new Container();
    }

    /**
     * 获取正在运行的kernel实例
     * @return Kernel
     */
    public static function instance()
    {
        return static::$kernel;
    }
}
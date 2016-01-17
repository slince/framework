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
use Slince\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Response;
use Slince\Routing\RouteCollection;
use Slince\Config\Config;

abstract class Kernel
{

    /**
     * Container Instance
     *
     * @var Container
     */
    protected $container;

    /**
     * 注册的application
     * 
     * @var array
     */
    protected $applications = [];
    
    /**
     * 被调度的application
     * 
     * @var ApplicationInterface
     */
    protected $application;
    
    public function __construct()
    {
        $this->container = $this->createContainer();
        $this->registerServices($this->container);
        $this->registerConfigs($this->container->get('config'));
        $this->registerSubscribers($this->container->get('dispatcher'));
        $this->registerRoutes($this->container->get('router')->getRoutes());
        $this->registerApplications();
        $this->dispatchEvent(EventStore::KERNEL_INITED);
    }

    /**
     * 注册application
     * 
     * @param ApplicationInterface $application
     */
    public function registerApplication(ApplicationInterface $application)
    {
        $this->applications[$application->getName()] = $application;
    }

    /**
     * 注册所有application
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
     * 注册subscriber
     * 
     * @param Dispatcher $dispatcher
     */
    abstract public function registerSubscribers(Dispatcher $dispatcher);

    /**
     * 注册route
     * 
     * @param RouteCollection $routes
     */
    abstract public function registerRoutes(RouteCollection $routes);

    /**
     * 获取DI容器
     *
     * @return \Slince\Di\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * 创建DI容器
     *
     * @return \Slince\Di\Container
     */
    protected function createContainer()
    {
        return new Container();
    }
    
    /**
     * 运行项目
     */
    public function run()
    {
        $request = Request::createFromGlobals();
        $response = $this->handleRequest($request);
        $response->sendContent();
        exit();
    }
    
    /**
     * 开始处理请求
     * 
     * @param Request $request
     * @return Response
     */
    public function handleRequest(Request $request)
    {
        $route = $this->container->get('router')->match($request->getPathInfo());
        $this->container->get('kernelCache')->set('request', $request);
        $this->container->get('kernelCache')->set('route', $route);
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
            $response = $this->runApplication($applicationName, $controller, $action, $route->getParameters());
        }
        return $response;
    }

    /**
     * 核心内存缓存参数读取
     * 
     * @param string $name
     * @param string $default
     */
    function getParameter($name, $default = null)
    {
        return $this->container->get('kernelCache')->get($name, $default);
    }
    
    /**
     * 派发事件
     * 
     * @param string $eventName
     * @param array $parameters
     */
    public function dispatchEvent($eventName, $parameters = [])
    {
        $event = new Event($eventName, $this, $this->container->get('dispatcher'), $parameters);
        $this->container->get('dispatcher')->dispatch($eventName, $event);
    }

    /**
     * 调度回调
     * 
     * @param string $action
     * @param array $parameters
     * @throws LogicException
     * @return Ambigous <\Symfony\Component\HttpFoundation\Response, mixed>
     */
    protected function runCallableAction($action, $parameters = [])
    {
        $response = call_user_func($action, $parameters);
        if (! $response instanceof Response) {
            throw new LogicException("Route action must return a response instance");
        }
        return $response;
    }

    /**
     * 运行application
     * 
     * @param string $applicationName
     * @param string $controllerName
     * @param unknown $actionName
     * @param unknown $parameters
     * @throws LogicException
     */
    protected function runApplication($name, $controller, $action, $parameters = [])
    {
        if (! isset($this->applications[$name])) {
            throw new LogicException("Application {$name} is not fund");
        }
        $this->application = $this->applications[$name];
        return $this->application->run($this, $controller, $action, $parameters);
    }

    protected function bindRequestToContext(Request $request, RequestContext $context)
    {
        $context->setBaseUrl($request->getBaseUrl());
        $context->setPathInfo($request->getPathInfo());
        $context->setMethod($request->getMethod());
        $context->setHost($request->getHost());
        $context->setScheme($request->getScheme());
        $context->setHttpPort($request->isSecure() ? $this->httpPort : $request->getPort());
        $context->setHttpsPort($request->isSecure() ? $request->getPort() : $this->httpsPort);
        $context->setQueryString($request->server->get('QUERY_STRING', ''));
        return $context;
    }
}
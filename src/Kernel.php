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

    protected $src = '';

    /**
     * Container Instance
     *
     * @var Container
     */
    protected $container;

    protected $applications = [];

    protected $routePathSeparator = '@';

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

    public function run()
    {
        $request = Request::createFromGlobals();
        $response = $this->request($request);
        $response->sendContent();
        exit();
    }

    public function registerApplication($name, ApplicationInterface $application)
    {
        $this->applications[$name] = $application;
    }

    abstract public function registerApplications();
    
    abstract public function registerServices(Container $container);
    
    abstract public function registerConfigs(Config $config);
    
    abstract public function registerSubscribers(Dispatcher $dispatcher);

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

    public function request(Request $request)
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
            list($applicationName, $controllerName, $action) = explode('@', $action);
            $response = $this->runApplication($applicationName, $controllerName, $action, $route->getParameters());
        }
        return $response;
    }

    function getParameter($name, $default = null)
    {
        return $this->container->get('kernelCache')->get($name, $default);
    }
    
    public function dispatchEvent($eventName, $parameters = [])
    {
        $event = new Event($eventName, $this, $this->container->get('dispatcher'), $parameters);
        $this->container->get('dispatcher')->dispatch($eventName, $event);
    }

    protected function runCallableAction($action, $parameters = [])
    {
        $response = call_user_func($action, $parameters);
        if (! $response instanceof Response) {
            throw new LogicException("Route action must return a response instance");
        }
        return $response;
    }

    protected function runApplication($applicationName, $controllerName, $actionName, $parameters = [])
    {
        if (! isset($this->applications[$applicationName])) {
            throw new LogicException("Application {$applicationName} is not fund");
        }
        $application = $this->applications[$applicationName];
        return $application->run($this, $controllerName, $actionName, $parameters);
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
    
    protected function createContainer()
    {
        return new Container();
    }
}
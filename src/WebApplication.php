<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller;
use Slince\Router\Router;
use Slince\Event\Event;
use Slince\Config\Repository;
use Cake\Utility\Inflector;

class WebApplication extends AbstractApplication
{

    /**
     * 
     * @var Request
     */
    protected $_controller;
    
    /**
     * request instance
     *
     * @var Request
     */
    protected $_request;

    /**
     * 路由解析实例
     *
     * @var Router
     */
    protected $_router;
    
    function __construct(Repository $config, Request $request)
    {
        $this->_request = $request;
        parent::__construct($config);
    }

    /**
     * 获取request
     *
     * @return Request
     */
    function getRequest()
    {
        return $this->_request;
    }
    /**
     * 获取router
     * 
     * @return Router
     */
    function getRouter()
    {
        return $this->_container->get('router');
    }
    /**
     * (non-PHPdoc)
     * @see \Slince\Applicaion\ApplicationInterface::run()
     */
    function run()
    {
        $response = $this->process();
        return $response;
    }

    /**
     * 处理request
     * 
     * @param Request $request
     * @return Response
     */
    function process()
    {
        $this->_dispatchEvent(EventStore::PROCESS_REQUEST);
        return $this->_dispatchRoute();
    }
    /**
     * 路由调度
     */
    function _dispatchRoute()
    {
        
        $route = $this->getRouter()->match($this->_request->getPathinfo());
        $action = $route->getParameter('action');
        list($controllerName, $actionName) = explode('@', $action);
        //存储路由信息
        $this->setParameter('controller', $controllerName);
        $this->setParameter('action', $actionName);
        $this->setParameter('prefix', $route->getPrefix());
        $this->setParameter('routeParameters', $route->getRouteParameters());
        $this->_dispatcher->bind(EventStore::DISPATCH_ROUTE, array(
            $this,
            '_invokeController'
        ));
        $this->_dispatchEvent(EventStore::DISPATCH_ROUTE);
    }
    
    /**
     * 调用controller
     * @param string $controllerName
     * @param string $actionName
     * @throws MissControllerException
     * @throws LogicException
     * @throws MissActionException
     * @return Response
     */
    function _invokeController()
    {
        $controllerClass = $this->_getControllerClass();
        $controller = $this->_container->get($controllerClass);
        if (empty($controller)) {
            throw new MissControllerException($controllerName);
        } 
        if(! $controller instanceof Controller) {
            throw new LogicException('Controller action can only return an instance of Response');
        }
        if (! method_exists($controller, $actionName)) {
            throw new MissActionException($controller, $actionName);
        }
        $response = $controller->invokeAction($this);
        return $response;
    }
    
    function _getControllerClass()
    {
        $controllerName = $this->getParameter('controller') . 'Controller';
        $namespace = '\\App\\Controller\\';
        $prefix = $this->getParameter('prefix');
        if (! empty($prefix)) {
            $namespace .= Inflector::classify(ltrim($prefix, '/')) . '\\';
        }
        return $namespace . $controllerName;
    }
}
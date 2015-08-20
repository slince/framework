<?php
namespace Slince\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller;
use Slince\Router\Router;
use Slince\Router\Route;
use Slince\Event\Event;

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
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Applicaion\ApplicationInterface::run()
     */
    function run(Request $request)
    {
        $response = $this->process($request);
        $response->send();
    }

    /**
     * 处理request
     * 
     * @param Request $request
     * @return Response
     */
    function process($request)
    {
        $this->_request = $request;
        $this->_dispatcher->dispatch(EventStore::PROCESS_REQUEST);
        return $this->_dispatchRoute();
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
     * 路由调度
     */
    function _dispatchRoute()
    {
        $this->_route = $this->_di->get('router')->match($this->_request->getPathinfo());
        $action = $route->getParameter('action');
        list($controllerName, $actionName) = explode('@', $action);
        //存储路由信息
        $this->setParameter('controller', $controllerName);
        $this->setParameter('action', $actionName);
        $this->setParameter('prefix', $route->getPrefix());
        $this->setParameter('routeParameters', $route->getRouteParameters());
        $this->_dispatcher->bind(EventStore::APP_DISPATCH_ROUTE, array(
            $this,
            '_invokeController'
        ));
        $this->_dispatcher->dispatch(EventStore::APP_DISPATCH_ROUTE, new Event(
            EventStore::APP_DISPATCH_ROUTE, $this, $this->_dispatcher
        ));
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
    function _invokeController($controllerName, $actionName)
    {
        $controller = $this->get($controllerName);
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
}
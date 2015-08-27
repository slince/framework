<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Slince\Router\Router;
use Slince\Event\Event;
use Slince\Config\Repository;
use Cake\Utility\Inflector;
use Slince\Application\Exception;

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
     * (non-PHPdoc)
     * @see \Slince\Applicaion\ApplicationInterface::run()
     */
    function run()
    {
        parent::run();
        $response = $this->_process();
        return $response;
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
     * 处理request
     * 
     * @param Request $request
     * @return Response
     */
    protected function _process()
    {
        $this->_dispatchEvent(EventStore::PROCESS_REQUEST);
        return $this->_dispatchRoute();
    }
    /**
     * 路由调度
     */
    protected function _dispatchRoute()
    {
        
        $route = $this->getRouter()->match($this->_request->getPathinfo());
        $action = $route->getParameter('action');
        list($controllerName, $actionName) = explode('@', $action);
        //存储路由信息
        $this->setParameter('controller', $controllerName);
        $this->setParameter('action', $actionName);
        $this->setParameter('prefix', $route->getPrefix());
        $this->setParameter('routeParameters', $route->getRouteParameters());
        //绑定controller调用
        $this->_dispatcher->bind(EventStore::DISPATCH_ROUTE, array(
            $this,
            'invokeController'
        ));
        //接下来是response传递的事件调度过程
        $response = new Response();
        $this->_dispatchEvent(EventStore::DISPATCH_ROUTE, [
            'response' => $response
        ]);
        return $response;
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
    function invokeController(Event $event)
    {
        $controllerClass = $this->_getControllerNamedClass();
        $controller = $this->_container->get($controllerClass);
        if (empty($controller)) {
            throw new MissControllerException($controllerName);
        } 
        if(! $controller instanceof Controller) {
            throw new LogicException('Controller action can only return an instance of Response');
        }
        $actionName = $this->getParameter('action');
        if (! method_exists($controller, $actionName)) {
            throw new MissActionException($controller, $actionName);
        }
        $controller->invokeAction($event);
    }
    
    /**
     * 获取Controller的完整 class name
     * 
     * @return string
     */
    protected function _getControllerNamedClass()
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
<?php
namespace Slince\Applicaion;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller;

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
     * @var 
     */
    protected $_router;
    
    /**
     * (non-PHPdoc)
     * @see \Slince\Applicaion\ApplicationInterface::run()
     */
    function run($request)
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
     * 路由调度
     */
    function _dispatchRoute()
    {
        $route = $this->_di->get('router')->match($this->_request->getPathinfo());
        $action = $route->getParameter('action');
        list($controllerName, $actionName) = explode('@', $action);
        //存储路由信息
        $this->_request->setParameter('controller', $controllerName);
        $this->_request->setParameter('action', $actionName);
        $this->_request->setParameter('prefix', $route->getPrefix());
        $response = $this->_invokeController($controllerName, $actionName);
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
        $response = $controller->invokeAction($actionName);
        return $response;
    }
}
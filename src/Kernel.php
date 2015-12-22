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

class Kernel
{
    protected $_rootPath = '';
    
    protected $_src = '';
    
    protected $_applications = [];
    
    /**
     * Container Instance
     *
     * @var Container
     */
    protected $_container;
    
    /**
     * Dispatcher Instance
     *
     * @var Dispatcher
     */
    protected $_dispatcher;
    
    function registerApplication(ApplicationInterface $application, $name= null)
    {
        if (is_null($name)) {
            $name = get_class($application);
        }
        $this->_applications[$name] = $application;
    }
    

    /**
     * 获取DI容器
     * 
     * @return \Slince\Di\Container
     */
    function getContainer()
    {
        return $this->_container;
    }
    
    function request(Request $request)
    {
        $router
    }
    
    protected function _getAction(Request $request)
    {
        $router = $this->getContainer()->get('router');
        $route = $router->match($request->getPathInfo());
        return $route;
    }
    
    protected function _bindRequestToContext(Request $request, RequestContext $context)
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
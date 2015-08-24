<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Slince\Application\Exception\LogicException;
use Slince\Event\Event;

class Controller
{
    protected $app;
    
    protected $request;
    
    protected $response;
    
    function getRequest()
    {
        return $this->request;
    }
    
    function getResponse()
    {
        $this->response;
    }
    
    function render()
    {
        return $this->response;
    }
    
    /**
     * 获取ViewManager
     * 
     * @\Slince\View\Engine\Native\ViewManager
     */
    function getView()
    {
        
        return $this->app->getContainer()->get('view');
    }
    
    /**
     * 与application交互的接口，返回response
     * 
     * @param WebApplication $app
     * @throws LogicException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function invokeAction(Event $event)
    {
        $app = $event->getSubject();
        $this->app = $app;
        $this->request = $app->getRequest();
        $this->response = $event->getArgument('response');
        $action = $app->getParameter('action');
        $response = call_user_func([$this, $action], $app->getParameter('routeParameters', []));
        if (is_null($response)) {
            $this->render();
        } elseif(! $response instanceof Response) {
            throw new LogicException('Controller action can only return an instance of Response');
        }
    }
}
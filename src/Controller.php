<?php
namespace Slince\Applicaion;

use Symfony\Component\HttpFoundation\Request;

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
    
    function render();
    
    function invokeAction(WebApplication $app)
    {
        $this->app = $app;
        $this->request = $app->getRequest();
        $action = $app->getParameter('action');
        $this->response = call_user_func([$this, $action], $app->getParameter('routeParameters', []));
        return $this->response;
    }
}
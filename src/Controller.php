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
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;

class Controller
{
    /**
     * app
     * 
     * @var Application
     */
    protected $application;
    
    /**
     * request
     * 
     * @var Request
     */
    protected $request;
    
    /**
     * response
     * 
     * @var Response
     */
    protected $response;
    
    protected $action;
    
    /**
     * view manager
     * 
     * @var \Slince\View\Engine\Native\ViewManager
     */
    protected $viewManager;
    
    protected $rendered = false;
    
    function __construct(ApplicationInterface $application)
    {
        $this->application = $application;
        $this->request = $application->getKernel()->getParameter('request');
        $this->response = new Response();
    }
    
    function __get($name)
    {
        return $this->loadModel($name);
    }
    
    /**
     * 获取request
     * 
     * @return \Symfony\Component\HttpFoundation\Request
     */
    function getRequest()
    {
        return $this->request;
    }
    
    /**
     * 获取response
     */
    function getResponse()
    {
        $this->response;
    }
    
    function loadModel($modelClass)
    {
        $this->$modelClass = TableRegistry::get($modelClass);
        return $this->$modelClass;
    }
    
    /**
     * 渲染模板
     * 
     * @param string $templateName
     * @param string $layout
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function render($templateName = null, $layout = true)
    {
        //如果没有选择render，则取当前action
        if (is_null($templateName)) {
            $templateName = $this->action;
        }
        $content = $this->getViewManager()->load($templateName, $layout)->render();
        $this->response->setContent($content);
        $this->rendered = true;
        return $this->response;
    }
    
    /**
     * 获取ViewManager
     * 
     * @return \Slince\View\Engine\Native\ViewManager
     */
    function getViewManager()
    {
        if (is_null($this->viewManager)) {
            $controllerDir = $this->app->getParameter('controller');
            $viewManager = $this->app->getContainer()->get('view');
            $viewManager->setViewPath($viewManager->getViewPath() . '/' . $controllerDir);
            $this->viewManager = $viewManager;
        }
        return $this->viewManager;
    }
    
    /**
     * 与application交互的接口，返回response
     * 
     * @param WebApplication $app
     * @throws LogicException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function invokeAction($action, $parameters)
    {
        $this->action = $action;
        $response = call_user_func([$this, $action], $parameters);
        //如果没有返回response并且没有渲染过视图则渲染视图
        if (is_null($response)) {
            if (! $this->rendered) {
                $this->render();
            }
        } else {
            if(! $response instanceof Response) {
                throw new LogicException('Controller action can only return an instance of Response');
            } else {
                $this->response = $response;
            }
        }
        return $this->response;
    }
}
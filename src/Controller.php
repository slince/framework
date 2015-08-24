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

class Controller
{
    /**
     * app
     * 
     * @var WebApplication
     */
    protected $app;
    
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
    
    /**
     * view manager
     * 
     * @var \Slince\View\Engine\Native\ViewManager
     */
    protected $viewManager;
    
    protected $rendered = false;
    
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
            $templateName = $this->app->getParameter('action');
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
    function invokeAction(Event $event)
    {
        $app = $event->getSubject();
        $this->app = $app;
        $this->request = $app->getRequest();
        $this->response = $event->getArgument('response');
        $action = $app->getParameter('action');
        $response = call_user_func([$this, $action], $app->getParameter('routeParameters', []));
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
    }
}
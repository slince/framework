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
    
    /**
     * request action
     * 
     * @var string
     */
    protected $action;
    
    /**
     * view manager
     * 
     * @var \Slince\View\Engine\Native\ViewManager
     */
    protected $viewManager;
    
    /**
     * 是否渲染过view
     * 
     * @var boolean
     */
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
    
    /**
     * load table
     * 
     * @param string $modelClass
     * @param array $options
     */
    function loadModel($modelClass, array $options = [])
    {
        if ($applicationName = strstr($modelClass, '::', true)) {
            $namespace = $this->application->getKernel()->getApplication($applicationName)->getNamespace();
        } else {
            $namespace = $this->application->getNamespace();
        }
        if (! isset($options['className'])) {
            $options['className'] =  "{$namespace}\\Model\\Table\\{$modelClass}Table";
        }
        $this->$modelClass = TableRegistry::get($modelClass, $options);
        return $this->$modelClass;
    }
    
    /**
     * 渲染模板
     * 
     * @param string $template
     * @param string $layout
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function render($template = null, $layout = true)
    {
        //如果没有选择render，则取当前action
        if (is_null($template)) {
            $template = $this->action;
        }
        $content = $this->getViewManager()->load($template, $layout)->render();
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
            $rootPath = $this->application->getRootPath();
            $controllerDir = Inflector::tableize(substr(basename(get_class($this)), 0, -10));
            $viewManager = $this->application->getKernel()->getContainer()->get('view');
            $viewManager->setViewPath("{$rootPath}views/templates/{$controllerDir}/");
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
        $response = $this->reflectAndInvokeAction($action, $parameters);
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
    
    /**
     * 反射并执行action
     * 
     * @param string $action
     * @param array $parameters
     * @return mixed
     */
    protected function reflectAndInvokeAction($action, $parameters)
    {
        $reflectionMethod = new \ReflectionMethod($this, $action);
        $arguments = [];
        foreach ($reflectionMethod->getParameters() as $parameter) {
            $parameterName = $parameter->getName();
            if (isset($parameters[$parameterName])) {
                $arguments[] = $parameters[$parameterName];
            } elseif ($parameter->isOptional()) {
                $arguments[] = $parameter->getDefaultValue();
            } else {
                $arguments[] = null;
            }
        }
        return $reflectionMethod->invokeArgs($this, $arguments);
    }
}
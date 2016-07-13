<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

use Cake\ORM\Table;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Slince\Application\Exception\LogicException;
use Cake\ORM\TableRegistry;
use Slince\Di\Container;

class Controller
{

    /**
     * app
     * @var Application
     */
    protected $application;

    /**
     * request
     * @var Request
     */
    protected $request;

    /**
     * response
     * @var Response
     */
    protected $response;

    /**
     * request action
     * @var string
     */
    protected $action;

    /**
     * layout
     * @var string
     */
    protected $layout;

    /**
     * 视图变量
     * @var array
     */
    protected $viewVariables = array();

    /**
     * 是否渲染过view
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
     * @return Request
     */
    function getRequest()
    {
        return $this->request;
    }

    /**
     * 获取response
     * @return Response
     */
    function getResponse()
    {
        return $this->response;
    }

    /**
     * 获取container
     * @return Container
     */
    function getContainer()
    {
        return $this->application->getKernel()->getContainer();
    }

    /**
     * load table
     * @param string $modelClass
     * @param array $options
     * @return Table
     */
    function loadModel($modelClass, array $options = [])
    {
        if ($applicationName = strstr($modelClass, '::', true)) {
            $namespace = $applicationName == 'App' ? 'App' : $this->application->getKernel()
                ->getApplication($applicationName)
                ->getNamespace();
            $modelClass = strstr($modelClass, '::');
        } else {
            $namespace = $this->application->getNamespace();
        }
        if (! isset($options['className'])) {
            $options['className'] = "{$namespace}\\Model\\Table\\{$modelClass}Table";
        }
        $this->$modelClass = TableRegistry::get($modelClass, $options);
        return $this->$modelClass;
    }

    /**
     * 设置view变量
     * @param string $name
     * @param mixed $value            
     */
    function set($name, $value)
    {
        if (is_array($name)) {
            $this->sets($name);
        } else {
            $this->viewVariables[$name] = $value;
        }
    }

    function sets($variables)
    {
        $this->viewVariables = array_merge($this->viewVariables, $variables);
    }

    /**
     * 渲染模板
     * @param string $template
     * @param boolean $withLayout
     * @return Response
     */
    function render($template = null, $withLayout = true)
    {
        // 如果没有选择render，则取当前action
        if (is_null($template)) {
            $template = $this->action;
        }
        $content = $this->application->getViewManager()
            ->load($template, $this->layout)
            ->render($this->viewVariables, $withLayout);
        $this->response->setContent($content);
        $this->rendered = true;
        return $this->response;
    }

    /**
     * 与application交互的接口，返回response
     * @param string $action 要触发的action
     * @param array $parameters 路由参数
     * @throws LogicException
     * @return Response
     */
    function invokeAction($action, $parameters)
    {
        $this->action = $action;
        $response = $this->reflectAndInvokeAction($action, $parameters);
        // 如果没有返回response并且没有渲染过视图则渲染视图
        if (is_null($response)) {
            if (! $this->rendered) {
                $this->render();
            }
        } else {
            if (! $response instanceof Response) {
                throw new LogicException('Controller action can only return an instance of Response');
            } else {
                $this->response = $response;
            }
        }
        return $this->response;
    }

    /**
     * 反射并执行action
     * @param string $action
     * @param array $parameters            
     * @return mixed
     */
    protected function reflectAndInvokeAction($action, $parameters)
    {
        $reflectionMethod = new \ReflectionMethod($this, $action);
        $arguments = [];
        // 匹配的路由参数只有在action参数中被列出来的才会被注入
        foreach ($reflectionMethod->getParameters() as $parameter) {
            $parameterName = $parameter->getName();
            if (isset($parameters[$parameterName])) {
                $arguments[] = $parameters[$parameterName];
            } elseif ($parameter->isOptional()) {
                $arguments[] = $parameter->getDefaultValue();
            } else {
                //如果没有在路由参数中匹配到则置null
                $arguments[] = null;
            }
        }
        return $reflectionMethod->invokeArgs($this, $arguments);
    }
}
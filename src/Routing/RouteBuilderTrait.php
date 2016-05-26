<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

trait RouteBuilderTrait
{
    /**
     * 路由前缀
     *
     * @var string
     */
    protected $prefix = '';

    function setPrefix($prefix)
    {
        if (!empty($prefix)) {
            $this->prefix = '/' . trim($prefix, '/');
        }
    }

    /**
     * 获取当前的前缀
     * @return string
     */
    function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * 创建一个普通路由，addRoute别名
     * @param $path
     * @param $arguments
     * @return Route
     */
    function http($path, $arguments)
    {
        return $this->addRoute($path, $arguments);
    }

    /**
     * 创建一个https路由
     * @param $path
     * @param $arguments
     * @return $this
     */
    function https($path, $arguments)
    {
        return $this->addRoute($path, $arguments)->setSchemes([
            'https'
        ]);
    }

    /**
     * 创建一个get路由
     * @param $path
     * @param $arguments
     * @return $this
     */
    function get($path, $arguments)
    {
        return $this->addRoute($path, $arguments)->setMethods([
            HttpMethod::GET,
            HttpMethod::HEAD
        ]);
    }

    /**
     * 创建一个post路由
     * @param $path
     * @param $arguments
     * @return $this
     */
    function post($path, $arguments)
    {
        return $this->addRoute($path, $arguments)->setMethods([
            HttpMethod::POST
        ]);
    }

    /**
     * 创建一个put路由
     * @param $path
     * @param $arguments
     * @return $this
     */
    function put($path, $arguments)
    {
        return $this->addRoute($path, $arguments)->setMethods([
            HttpMethod::PUT
        ]);
    }

    /**
     * 创建一个patch路由
     * @param $path
     * @param $arguments
     * @return $this
     */
    function patch($path, $arguments)
    {
        return $this->addRoute($path, $arguments)->setMethods([
            HttpMethod::PATCH
        ]);
    }

    /**
     * 创建一个delete路由
     * @param $path
     * @param $arguments
     * @return $this
     */
    function delete($path, $arguments)
    {
        return $this->addRoute($path, $arguments)->setMethods([
            HttpMethod::DELETE
        ]);
    }

    /**
     * 创建并添加一个路由
     * @param $path
     * @param $arguments
     * @return Route
     */
    function addRoute($path, $arguments)
    {
        $name = null;
        $action = null;
        if (is_callable($arguments) || is_string($arguments)) {
            $action = $arguments;
        } elseif (is_array($arguments)) {
            $name = isset($arguments['name']) ? $arguments['name'] : null;
            $action = isset($arguments['action']) ? $arguments['action'] : $arguments[0];
        }
        $route = $this->newRoute($path, $action);
        $this->getRoutes()->add($route, $name);
        return $route;
    }

    /**
     * 创建一个路由
     * @param $path
     * @param $action
     * @return Route
     */
    function newRoute($path, $action)
    {
        $path = $this->getPreifx() . '/' . trim($path, '/');
        return new Route($path, $action);
    }

    /**
     * 创建一个前缀
     * @param string $prefix
     * @param \Closure $callback
     */
    function prefix($prefix, \Closure $callback)
    {
        $originPrefix = $this->getPreifx();
        $this->setPrefix($originPrefix . '/' . $prefix);
        call_user_func($callback, $this);
        $this->setPrefix($originPrefix);
    }

    /**
     * 返回适配的route collection
     * @return RouteCollection
     */
    abstract function getRoutes();
}
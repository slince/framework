<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

trait RouteBuilderTrait
{

    /**
     * 创建一个普通路由，addRoute别名
     *
     * @param string $path            
     * @param array $arguments            
     */
    function http($path, $arguments)
    {
        return $this->addRoute($path, $arguments);
    }

    /**
     * 创建一个https路由
     *
     * @param string $path            
     * @param array $arguments            
     */
    function https($path, $arguments)
    {
        return $this->addRoute($path, $arguments)->setSchemes([
            'https'
        ]);
    }

    /**
     * 创建一个get路由
     *
     * @param string $path            
     * @param array $arguments            
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
     *
     * @param string $path            
     * @param array $arguments            
     */
    function post($path, $arguments)
    {
        return $this->addRoute($path, $arguments)->setMethods([
            HttpMethod::POST
        ]);
    }

    /**
     * 创建一个put路由
     *
     * @param string $path            
     * @param array $arguments            
     */
    function put($path, $arguments)
    {
        return $this->addRoute($path, $arguments)->setMethods([
            HttpMethod::PUT
        ]);
    }

    /**
     * 创建一个patch路由
     *
     * @param string $path            
     * @param array $arguments            
     */
    function patch($path, $arguments)
    {
        return $this->addRoute($path, $arguments)->setMethods([
            HttpMethod::PATCH
        ]);
    }

    /**
     * 创建一个delete路由
     *
     * @param string $path            
     * @param array $arguments            
     */
    function delete($path, $arguments)
    {
        return $this->addRoute($path, $arguments)->setMethods([
            HttpMethod::DELETE
        ]);
    }

    /**
     * 创建并添加一个路由
     *
     * @param string $path            
     * @param array $arguments            
     */
    function addRoute($path, $arguments)
    {
        $action = null;
        $name = null;
        if (is_callable($arguments)) {
            $action = $arguments;
        } elseif(is_array($arguments)) {
            $action = isset($arguments['action']) ? $arguments['action'] : $arguments[0];
            $name = isset($arguments['name']) ? $arguments['name'] : null;
        }
        $route = $this->newRoute($path, $action);
        if (is_string($name)) {
            $route->setParameter('name', $name);
        } 
        $this->getRouteCollection()->add($route);
        return $route;
    }

    /**
     * 创建一个路由
     *
     * @param string $path            
     * @param array $arguments            
     * @return Route
     */
    function newRoute($path, $action)
    {
        return new Route($path, $action);
    }

    /**
     * 创建一个前缀
     *
     * @param string $prefix            
     * @param \Closure $callback            
     */
    function prefix($prefix, \Closure $callback)
    {
    }

    /**
     * 返回适配的routecollection
     *
     * @return RouteCollection
     */
    abstract function getRouteCollection();
}
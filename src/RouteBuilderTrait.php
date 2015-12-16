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
     * @param array $parameters            
     */
    function http($path, $parameters)
    {
        return $this->addRoute($path, $parameters);
    }

    /**
     * 创建一个https路由
     *
     * @param string $path            
     * @param array $parameters            
     */
    function https($path, $parameters)
    {
        return $this->addRoute($path, $parameters)->setSchemes([
            'https'
        ]);
    }

    /**
     * 创建一个get路由
     *
     * @param string $path            
     * @param array $parameters            
     */
    function get($path, $parameters)
    {
        return $this->addRoute($path, $parameters)->setMethods([
            HttpMethod::GET,
            HttpMethod::HEAD
        ]);
    }

    /**
     * 创建一个post路由
     *
     * @param string $path            
     * @param array $parameters            
     */
    function post($path, $parameters)
    {
        return $this->addRoute($path, $parameters)->setMethods([
            HttpMethod::POST
        ]);
    }

    /**
     * 创建一个put路由
     *
     * @param string $path            
     * @param array $parameters            
     */
    function put($path, $parameters)
    {
        return $this->addRoute($path, $parameters)->setMethods([
            HttpMethod::PUT
        ]);
    }

    /**
     * 创建一个patch路由
     *
     * @param string $path            
     * @param array $parameters            
     */
    function patch($path, $parameters)
    {
        return $this->addRoute($path, $parameters)->setMethods([
            HttpMethod::PATCH
        ]);
    }

    /**
     * 创建一个delete路由
     *
     * @param string $path            
     * @param array $parameters            
     */
    function delete($path, $parameters)
    {
        return $this->addRoute($path, $parameters)->setMethods([
            HttpMethod::DELETE
        ]);
    }

    /**
     * 创建并添加一个路由
     *
     * @param string $path            
     * @param array $parameters            
     */
    function addRoute($path, $parameters)
    {
        $route = $this->newRoute($path, $parameters);
        if (isset($parameters['as'])) {
            $route->setOption('name', $parameters['as']);
            $this->getRouteCollection()->addNamedRoute($parameters['as'], $route);
        } else {
            $this->getRouteCollection()->add($route);
        }
        return $route;
    }

    /**
     * 创建一个路由
     *
     * @param string $path            
     * @param array $parameters            
     * @return Route
     */
    function newRoute($path, $parameters)
    {
        return new Route($path, $parameters);
    }

    /**
     * 创建一个前缀
     *
     * @param string $prefix            
     * @param \Closure $callback            
     */
    function prefix($prefix, \Closure $callback)
    {
        $routes = RouteCollection::create();
        $this->getRouteCollection()->addCollection($prefix, $routes);
        call_user_func($callback, $routes);
    }

    /**
     * 返回适配的routecollection
     *
     * @return RouteCollection
     */
    abstract function getRouteCollection();
}
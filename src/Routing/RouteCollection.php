<?php
/**
 * slince routing library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Routing;

class RouteCollection implements \Countable, \IteratorAggregate
{
    /**
     * route集合
     * @var array
     */
    protected $routes = [];

    /**
     * name集合
     * @var array
     */
    protected $names = [];

    /**
     * action集合
     * @var array
     */
    protected $actions = [];

    function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * 添加路由
     * @param RouteInterface $route
     * @param string|null $name
     */
    function add(RouteInterface $route, $name = null)
    {
        if (!is_null($name)) {
            $this->names[$name] = $route;
        }
        $action = $route->getAction();
        if (is_scalar($action)) {
            $this->actions[$action] = $route;
        }
        $this->routes[] = $route;
    }

    /**
     * 根据name获取route
     * @param string $name
     * @return Route|null
     */
    function getByName($name)
    {
        return isset($this->names[$name]) ? $this->names[$name] : null;
    }

    /**
     * 根据action获取route
     * @param string $action
     * @return Route|null
     */
    function getByAction($action)
    {
        return isset($this->actions[$action]) ? $this->actions[$action] : null;
    }

    /**
     * 获取全部的命名路由
     * @return array
     */
    function getNameRoute()
    {
        return $this->names;
    }

    /**
     * 获取全部action路由
     * @return array
     */
    function getActionRoutes()
    {
        return $this->actions;
    }

    /**
     * 获取所有的路由
     * @return array
     */
    function all()
    {
        return $this->routes;
    }

    /**
     * 获取路由数量
     * @return int
     */
    function count()
    {
        return count($this->routes);
    }

    /**
     * 实现接口
     * @return \ArrayIterator
     */
    function getIterator()
    {
        return new \ArrayIterator($this->routes);
    }

    /**
     * 实现RouteBuilderTrait方法
     * @return $this
     */
    function getRoutes()
    {
        return $this;
    }
}